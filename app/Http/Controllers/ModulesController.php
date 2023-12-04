<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Models\Admin;
use App\Models\InstallImage;
use App\Models\UserActivity;
use App\Notifications\Step3;
use App\Notifications\RSM;
use App\Notifications\UserNote;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Module;
use App\Models\ModuleQuiz;
use App\Models\ModuleAnswer;
use App\Models\RegionalRep;
use App\Models\Note;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use App\Traits\QuizResults;
use Illuminate\Support\Facades\DB;
use Auth;
use Monolog\Handler\FingersCrossed\ActivationStrategyInterface;

class ModulesController extends Controller
{
    use QuizResults;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    function index($id) {
        $user     = Auth::user();
        $module   = Module::find($id);
        $activity = UserActivity::where('user_id', $user->id)->first();
        $docs     = InstallImage::where('user_id', $user->id)->get();
        $note     = Note::where('user_id', $user->id)->where('module_id', $id)->where('admin_id', null)->first();
        $msgs     = Message::where('user_id', $user->id)->get();

        // make sure the module id pulls an existing module
        // else redirect to home
        if  ($module ?? null ) {
            return view('modules',[ 'module'=>$module, 'user'=>$user, 'activity'=>$activity, 'docs'=>$docs, 'note'=>$note, 'msgs' => $msgs ]);
        }
        else {
            return redirect()->route('home');
        }

    }

    function quiz($id) {
        $user = Auth::user();

        $module = Module::find($id);

        // use the Trait QuizResults to pull all answers the user has completed and compare it against all questions per module
        // this returns total questions in the db (under 't'), questions answered (under 'q'), and questions answered correctly ('a')
        $qNa = $this->getQuizResults();

        // Don't allow  for someone to put in a module id that doesn't exist in the db
        if ($qNa['t'][$id] ?? null) {
        } else {
            return redirect()->route('home');
        }

        // If no questions are found, notify the user
        if ( $qNa['t'][$id] == 0 ) {
            return redirect()->route('home');
        }
        else {
            // pull last answered question by user for that module and add 1.
            $last = ModuleAnswer::where('user_id', $user->id)
                ->where('module_id', $module->id)
                ->orderBy('id', 'desc')
                ->first();

            $next1 = ( $last == null ) ? 1 : $last->q_id + 1;

            // Get the next question. If nothing there, go to results
            $quiz = ModuleQuiz::where('module_id', $module->id)
                ->where('q_id', $next1)
                ->get();

            if ( count($quiz) > 0 ) {
                return view('quiz',['module'=>$module, 'quiz'=>$quiz, 'user'=>$user]);
            }
            else {
                $mod_id = 'module_' . sprintf("%02d", $module->id);

                $next_mod = $module->id + 1;

                $mod_check = Module::where('id', $next_mod)->first();
                $q_list = ModuleQuiz::where('module_id', $module->id)->get();
                $a_list = ModuleAnswer::where('module_id', $module->id)->where('user_id', $user->id)->get();
                $activity = UserActivity::where('user_id', $user->id)->first();

                $perc = $qNa['a'][$module->id] / $qNa['t'][$module->id] * 100;

                // Post percentage to user_activities
                UserActivity::where('user_id', $user->id)
                    ->update([
                        $mod_id=>$perc
                    ]);
                return view('result', ['module'=>$module, 'user'=>$user, 'activity'=>$activity,'next'=>$next_mod, 'mod_check'=>$mod_check, 'q_tot'=>$qNa['t'], 'questions'=>$qNa['q'], 'answers'=>$qNa['a'], 'q_list'=>$q_list, 'a_list'=>$a_list]);
            }
        }
    }

    function requestCert() {
            $user = Auth::user();

            $activity = UserActivity::where('user_id', $user->id)->first();
            $super = RegionalRep::all();

            // check if the user has already been tagged as having finished their training
            // If not, pull the admin assigned to their account
            // Send the admin an email notifying them of the completion
            if ( $activity->training_done == null ) {

                // get the user's admin to send them a notification
                $admin = Admin::where('id', $user->admin_id)->first();

                // if no returned admin or a mismatch occurred, default admin to first one found
                if ($admin == null) {
                    $admin = Admin::first();
                }

                // Get user's specified state (NEED TO CREATE A JOIN FOR THIS AND THE RSM BELOW)
                $state = DB::table('usa_states')->where('abbrev', strtoupper($user->states))->orWhere('name', ucwords($user->states))->first();

                if ($state) {
                    // Get RSM for user's state
                    $rep = RegionalRep::where('id', $state->rep)->first();

                    // Get all super_admins
                    $super = Admin::where('id', '!=', $admin->id)->where('super_admin', 1)->get();

                    // send notification to the RSM for the user's state
                    if ($rep) {
                        $rep->notify(new RSM([
                            'subject' => 'User Certification',
                            'intro' => '<strong>User training completion.</strong>',
                            'message' => $user->first_name . ' ' . $user->last_name . ' (company: ' . $user->companies . ', state: ' . $state->name .') has completed the Sanctuary certification training.' .
                                '<p>The ESS Team has been notified. Please verify that this user gets certified in a timely manner.</p>',
                        ]));
                    }
                }

                // send notification to the assigned admin that they have been certified
                $admin->notify(new Step3([
                    'subject' => 'User Certification',
                    'intro'   => '<strong>User training completion.</strong>',
                    'message' => $user->first_name . ' ' . $user->last_name . ' (company: ' . $user->companies . ', state: ' . $state->name . ') has completed the Sanctuary certification training and is ready to be registered as a certified installer',
                    'outtro'  => 'Click the button to review their progress and certify them.',
                    'url'     => route('userDetail', ['id' => $user->id]),
                ]));

                // Send notification to supers with slightly different wording.
                foreach ( $super as $s ) {
                    $s->notify(new Step3([
                        'subject' => 'User Certification',
                        'intro'   => '<strong>User training completion.</strong>',
                        'message' => $user->first_name . ' ' . $user->last_name . ' (company: ' . $user->companies . ', state: ' . $state->name . ') has completed the Sanctuary certification training.',
                        'outtro'  => 'The ESS Team has been notified. Please verify that this user gets certified in a timely manner.',
                        'url'     => route('userDetail', ['id' => $user->id]),
                    ]));
                }

                UserActivity::where('user_id', $user->id)
                    ->update(['training_done' => 1 ]);
           }
            return redirect()->route('home');
    }

    function quizResults() {
        $activity = UserActivity::where('user_id', $user->id)->first();
        return view('result', ['module'=>$module, 'user'=>$user, 'answers'=>$answers, 'activity'=>$activity]);
    }

    function store(Request $request) {
        $validated = $request->validate(
            [
                'answer' => 'required'
            ]
        );

        $table = new ModuleAnswer;

        $table->answer    = $request->answer;
        $table->module_id = $request->module_id;
        $table->q_id      = $request->q_id;
        $table->user_id   = $request->user_id;

        $table->save();

        return redirect()->route('quiz', ['id' => $request->module_id ]);
    }

    function restartQuiz(Request $request) {

        // this will retain the answers in the database for cumulative analysis, but remove the users's ID from each line item.
        $user = Auth::user();
        if ( $request->isMethod('put')) {

            ModuleAnswer::where('module_id', $request->module_id)
                ->where('user_id', $user->id)
                ->update([
                    'user_id'=>0
                ]);

            $mod_activity = 'module_' . sprintf("%02d", $request->module_id);

            UserActivity::where('user_id', $user->id)
                ->update([
                   $mod_activity => null
                ]);
        }

        return redirect()->route('quiz', ['id'=>$request->module_id]);
    }

    function restartVideo(Request $request) {
        $user = Auth::user();
        if ( $request->isMethod('put')) {

            ModuleAnswer::where('module_id', $request->module_id)
                ->where('user_id', $user->id)
                ->update([
                    'user_id'=>0
                ]);
        }

        return redirect()->route('modules', ['id'=>$request->module_id]);
    }

    function uploadFiles(Request $request) {
        $user = Auth::user();

        foreach ($request->files as $key=>$value) {
            // get the file name and turn it into the title of the document.
            // force the text to lowercase, then make do first-letter caps
            // remove the extension
            $image_title = str_replace('_', ' ', $request->file($key)->getClientOriginalName());
            $image_ext = $request->file($key)->getClientOriginalExtension();

            $image_cat = 'image';

            if ( str_contains($key, 'image') ) {
                $validator = Validator::make( $request->all(),
                    [
                        'image'=>'required|max:10000|mimes:jpg,png,webp,jpeg,gif',
                    ]
                );

                if ( $validator->fails() ) {
                    session()->flash('img_error','Images must be under 10MB in size and of accepted file type.');
                    return redirect()->back();
                }
            }

            // check for file type based on form name images are 'image_x', one-line diagrams are 'oneline', other documents are 'doc_x'
            // use str_contains to look for the key word since 2 of them have '_x' included.
            elseif ( str_contains($key,'oneline') ) {
                $validator = Validator::make( $request->all(),
                    [
                        'oneline'=>'required|max:10000|mimes:pdf',
                    ]
                );

                if ( $validator->fails() ) {
                    session()->flash('oneline_error','File must be under 10MB in size and a PDF.');
                    return redirect()->back();
                }

                $image_cat = 'oneline';

                // Check for existing oneline doc. Only one document is allowed in this section
                // if found, delete it from the storage folder and the db row
                $existing = InstallImage::where('user_id', $user->id)
                    ->where('image_cat', 'oneline')->first();

                if ($existing) {
                    if (Storage::exists('public/' . $existing->image_path)) {
                        Storage::delete('public/' . $existing->image_path);
                        InstallImage::destroy($existing->id);
                    }
                }
            }
            elseif ( str_contains($key,'doc') ) {
                $validator = Validator::make( $request->all(),
                    [
                        'doc'=>'required|max:10000|mimes:pdf,doc,docx,xls,xlsx,txt,csv,ppt,pptx',
                    ]
                );

                if ( $validator->fails() ) {
                    session()->flash('doc_error','File must be under 10MB in size and an acceptable file type.');
                    return redirect()->back();
                }

                $image_cat = 'doc';
            }

            $image_path = $request->file($key)->store('uploads', 'public');

            $data = InstallImage::create([
                'user_id'     => $user->id,
                'module_id'   => $request->module_id,
                'image_cat'   => $image_cat,
                'image_title' => $image_title,
                'image_ext'   => $image_ext,
                'image_path'  => $image_path,
            ]);

        }
        session()->flash('success', 'File uploaded successfully.');

        return redirect()->back();
    }

    function AjaxUpload(Request $request) {
        $user = Auth::user();
        $file = $request->file;
        $type = $request->type;

        $image_name = $file->getClientOriginalName();
        $image_ext  = $file->getClientOriginalExtension();
        $image_cat  = $request->type;

        $image_path = $file->store('uploads', 'public');

        $data = InstallImage::create([
            'user_id'     => $user->id,
            'module_id'   => 4,
            'image_cat'   => $image_cat,
            'image_title' => $image_name,
            'image_ext'   => $image_ext,
            'image_path'  => $image_path,
        ]);

        $return = $data->id . ',' . $image_path;

        echo $return;
    }

    function AjaxDelete(Request $request) {
        $user = Auth::user();
        var_dump($request->file_id);
        // Check for existing oneline doc. Only one document is allowed in this section
        // if found, delete it from the storage folder and the db row
        $existing = InstallImage::where('id', $request->file_id)->first();

        if (Storage::exists('public/' . $existing->image_path)) {
            Storage::delete('public/' . $existing->image_path);
            InstallImage::destroy($existing->id);
            echo 'Deleted';
        }

    }

    function deleteFiles(Request $request) {
        $user = Auth::user();
        // Check for existing oneline doc. Only one document is allowed in this section
        // if found, delete it from the storage folder and the db row
        $existing = InstallImage::where('id', $request->id)->first();

        if (Storage::exists('public/' . $existing->image_path)) {
            Storage::delete('public/' . $existing->image_path);
            InstallImage::destroy($existing->id);
            session()->flash('success', 'File deleted.');
        }

        return redirect()->back();
    }

    function add_message(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required',
            'message' => 'required'
        ]);

        $data = new Message;
        $data->user_id = $request->user_id;
        $data->admin_id = $request->admin_id;
        $data->message = htmlentities($request->message);

        $data->save();

        // Send notification to admin
        $user = Auth::user();
        if ( $user->admin_id == null) {
            $admin = Admin::first();
        } else {
            $admin = Admin::where('id', $user->admin_id)->first();
        }

        $notify_data = [
            'intro' => $user->first_name . ' ' . $user->last_name . ' has added a note on their certification board.',
            'message' => new HtmlString($data->note),
            'url' => route('userDetail', ['id' => $user->id]),
        ];

        $admin->notify( new UserNote($notify_data) );

        if ( $request->module_id == null ) {
            return redirect()->route('home');
        }
        else {
            return redirect()->route('modules', ['id'=>$request->module_id]);
        }
    }

    function moduleNote(Request $request) {
        $validate = $request->validate([
            'note'      => 'required',
            'user_id'   => 'required',
            'module_id' => 'required',
        ]);

        $note = html_entity_decode($request->note);

        $note = htmlentities($note);

        Note::updateOrCreate(
            [ 'user_id' => $request->user_id, 'module_id' => $request->module_id, 'admin_id' => null ],
            [ 'note' => $note ]
        );

        return redirect()->back();
    }

    function submitReview(Request $request) {
        // Send email to admin to review images
        $user = Auth::user();

        // create proper column call. could be 'review_04', 'review_05'
        $mod_date = 'review_' . sprintf("%02d",$request->module_id);
        $rev_date = date("Y-m-d H:i:s");

        UserActivity::where('user_id', $user->id)
            ->update([
                $mod_date => $rev_date
            ]);

        return redirect()->route('home');
    }
}
