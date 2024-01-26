<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ModuleAnswer;
use App\Models\ModuleQuiz;
use Illuminate\Http\Request;
use App\Models\UserActivity;
use App\Models\Message;
use App\Models\User;
use App\Models\Module;
use App\Models\Resource;
use App\Models\InstallImage;
use App\Traits\QuizResults;
use Auth;
use Illuminate\Support\Facades\Session;
use App\Notifications\Step3;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    use QuizResults;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    // Use this function to get all the information on the user for use in views and functions
    protected function userPull() {
        $user = Auth::user();
        $companies = explode(',', $user->companies);
        $states = explode(',', $user->states);
        $activity = UserActivity::where('user_id', $user->id)->first();
        $images = InstallImage::where('user_id', $user->id)->get();
        $messages = Message::where('user_id', $user->id)->get();

        return ( ['user'=>$user, 'companies'=>$companies, 'states'=>$states, 'activity'=>$activity, 'images'=>$images, 'messages'=>$messages] );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        $user_info = $this->userPull();
        session(['user' => $user_info['user']['id']]);

        $modules   = Module::all();

        // Use Trait QuizResults to get all user answers to all module questions for display
        $qNa = $this->getQuizResults();

        return view('home')->with (
            [
                "user"      => $user_info['user'],
                "companies" => $user_info['companies'],
                "states"    => $user_info['states'],
                "activity"  => $user_info['activity'],
                "modules"   => $modules,
                "images"    => $user_info['images'],
                "messages"  => $user_info['messages'],
                "questions" => $qNa['q'],
                "answers"   => $qNa['a'],
            ]
        );
    }

    public function install() {
        $user_info = $this->userPull();

        return view('installation')->with(
            [
                'activity' => $user_info['activity'],
                'user'     => $user_info['user'],
                'images'   => $user_info['images']
            ]
        );
    }

    public function installUpdate(Request $request) {
        $user_info = $this->userPull();
        if ( empty( session('user' ) ) ) {
            session(['user' => $user_info['user']['id'] ]);
        }
        if ($request->isMethod('put')) {
            UserActivity::where('user_id', $request->session()->get('user'))->update([
                'install_address'=>$request->install_address,
                'install_city'=>$request->install_city,
                'install_state'=>$request->install_state,
                'install_zip'=>$request->install_zip,
                'install_batteries'=>$request->install_batteries,
                'install_inverters'=>$request->install_inverters,
                'module_03' => 1,
                'module_03_date'=>date("Y-m-d H:i:s"),
            ]);


            $notify_data = [
                'subject' => 'Installation Address Added',
                'intro' => $user_info['user']['name'] . ' has added an installation location on step 3.',
                'message' => 'Login to view their submission',
                'outtro' => '',
                'url' => route('userDetailStep', ['id' => $request->session()->get('user'), 'step' => 3]),
            ];

            if ( $user_info['user']['admin_id'] == null) {
                $admin = Admin::first();
            } else {
                $admin = Admin::find($user_info['user']['admin_id']);
            }

            $admin->notify( new Step3($notify_data) );

        }
        return redirect()->route('home');
    }

    public function step3dateChange(Request $request) {

        $user = Auth::user();

        $new_datetime = htmlentities($request->date) . ' ' . htmlentities($request->time) . ':00';
        $review_date = date("Y-m-d H:i:s", strtotime($new_datetime) );

        if ( UserActivity::where('user_id', $request->session()->get('user'))->update(
            [
                'review_03_user_request' => $new_datetime
            ])
        ) {
            Session::flash('step_4_success' . $request->session()->get('step'),'Date/time requested.');
        }
        else {
            Session::flash('step_4_error' . $request->session()->get('step'),'Date/time could not be saved. Contact your admin.');
        }

        // Send notification to admin
        if ( $user->admin_id == null) {
            $admin = Admin::first();
        } else {
            $admin = Admin::where('id', $user->admin_id)->first();
        }

        $notify_data = [
            'subject' => 'Appointment Change Request',
            'intro'   => $user->first_name . ' ' . $user->last_name . ' has requested a change to the appointment for the installation location and document review.',
            'message' => date('M d, Y @ h:i A', strtotime( $new_datetime ) ),
            'outtro'  => 'Please approve the change or contact the user to set a new time.',
            'url' => secure_url( route('userDetailStep', ['id'=>$user->id, 'step'=>3] ) ),
        ];

        $admin->notify( new Step3($notify_data) );

        return redirect()->back();
    }

    public function step6DateChange(Request $request) {

        $user = Auth::user();

        $new_datetime = htmlentities($request->date) . ' ' . htmlentities($request->time) . ':00';
        $review_date = date("Y-m-d H:i:s", strtotime($new_datetime) );

        if ( UserActivity::where('user_id', $request->session()->get('user'))->update(
            [
                'review_06_user_request' => $new_datetime
            ])
        ) {
            Session::flash('step_6_success' . $request->session()->get('step'),'Date/time requested.');
        }
        else {
            Session::flash('step_6_error' . $request->session()->get('step'),'Date/time could not be saved. Contact your admin.');
        }

        // Send notification to admin
        if ( $user->admin_id == null) {
            $admin = Admin::first();
        } else {
            $admin = Admin::where('id', $user->admin_id)->first();
        }

        $notify_data = [
            'subject' => 'Final Inspection Change Request',
            'intro'   => $user->first_name . ' ' . $user->last_name . " has requested a change to the <strong>final inspection</strong> date and time.",
            'message' => date('M d, Y @ h:i A', strtotime( $new_datetime ) ),
            'outtro'  => 'Please approve the change or contact the user to set a new time.',
            'url' => secure_url( route('userDetailStep', ['id'=>$user->id, 'step'=>3] ) ),
        ];

        $admin->notify( new Step3($notify_data) );

        return redirect()->back();
    }
    public function step6DateAccept(Request $request) {
        $user = Auth::user();

        if ( UserActivity::where('user_id', $user->id)->update(
            [
                'review_06' => htmlentities($request->new_datetime),
                'review_06_user_request' => null,
                'review_06_admin_request' => null
            ])
        ) {
            Session::flash('step_6_success' . $request->session()->get('step'),'Date/time accepted.');
        }
        else {
            Session::flash('step_6_error' . $request->session()->get('step'),'Date/time could not be saved. Contact your admin.');
        }

        return redirect()->back();
    }

    public function info() {
        $user_info = $this->userPull();
        return view('contact')->with(["user"=>$user_info['user']]);
    }

    public function infoProcess(Request $request) {
        if ($request->isMethod('put')) {
            User::where('id', $request->id)->update ([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'companies' => $request->companies,
                'states' => $request->states,
            ]);

        }
        return redirect()->route('home', ["update"=>'user']);
    }

}
