<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ModuleAnswer;
use App\Models\ModuleQuiz;
use App\Models\RegionalRep;
use App\Notifications\AdminPasswordReset;
use App\Notifications\UserNote;
use App\Notifications\Step3;
use App\Notifications\RSM;
use App\Notifications\Generic;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;

use App\Models\Admin;
use App\Models\AdminReset;
use App\Models\User;
use App\Models\UserInvite;
use App\Models\UserActivity;
use App\Models\Module;
use App\Models\InstallImage;
use App\Models\Message;
use App\Models\Note;

use Illuminate\Support\Facades\DB;

use App\Traits\QuizResults;

use Config;
use Auth;

class AdminAuthController extends Controller
{
    use QuizResults;

    function e($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    function getAuth() {
        return auth()->guard('admin')->user();
    }

    public function getLogin() {
        return view('admin.login');
    }

    public function postLogin(Request $request) {
        $this->validate($request, [
            'email' => 'required | email',
            'password' => 'required',
        ]);

        $email = filter_var($request->input('email'));
        $password = filter_var($request->input('password'));

        if( auth()->guard('admin')
            ->attempt([
                'email' => $email,
                'password' => $password,
            ])
        ) {
            $user = auth()->guard('admin')->user();
            if ( Hash::check($request->password, $user->password ) ) {
                return redirect()->route('adminDashboard');
            }
        }
        else {
            return redirect()->route('adminLogin', ['email' => $this->e($email)])->withErrors(['msg'=>'Invalid email and/or password.']);
        }
    }

    public function adminLogout(Request $request) {
        auth()->guard('admin')->logout();
        Session::flush();
        Session::flash('success','You are logged out.');
        return redirect()->route('adminLogin');
    }

    public function adminIndex(Request $request) {
        $admin = $this->getAuth();
        $modules = Module::all();

        $filter = !empty( $request->filter ) ? filter_var($request->filter) : null;
        $search = !empty( $request->search ) ? filter_var($request->search) : null;
        if (!empty($request->reset)) {
            Session::remove('search');
            $search = null;
        }

        // Combine the user account information with their activities in the separate table
        $users_all  = User::join('user_activities', 'users.id', '=', 'user_activities.user_id');
        $users_temp = $users_all;

        if ( !empty( $filter ) ) {
            if ($filter === 'certified') {
                $users_temp = $users_all->whereNotNull('cert');
            } elseif ($filter === 'finished') {
                $users_temp = $users_all->whereNull('cert')->whereNotNull('training_done');
            } elseif ($filter === 'unfinished') {
                $users_temp = $users_all->whereNull('cert')->whereNull('training_done');
            } elseif ($filter === 'rsm') {
                $users_temp = $users_all->where('admin_id', $admin->id);
            }
        }

        // counts for certs and training
        $users_count = $this->userJoin(null)->count();
        $certs       = $this->userJoin('certified')->count();
        $finished    = $this->userJoin('finished')->count();
        $unfinished  = $this->userJoin('unfinished')->count();
        $rsm         = $this->userJoin('rsm')->count();

        // Check if search field is used
        if ( !empty( $search ) ) {
            $users_temp  = $this->userSearch($users_temp, $search, $filter);
            $users_count = $this->userSearch($users_temp,$search)->count();
            $certs       = $this->userSearch($users_temp, $search, 'certified')->count();
            $finished    = $this->userSearch($users_temp, $search, 'finished')->count();
            $unfinished  = $this->userSearch($users_temp, $search, 'unfinished')->count();
            $rsm         = $this->userSearch($users_temp, $search, 'rsm')->count();
        }

        // Do the final pull of users
        $users = $users_temp->paginate(15);

        // Check for a search
        if ( !empty($search) ) {
            $users->appends(['search'=> $search]);
        }

        $user_stats = [
            'certs' => $certs,
            'finished' => $finished,
            'unfinished' => $unfinished,
            'rsm' => $rsm,
            'total' => $users_count,
        ];

        return view('admin.index', ['admin'=> $admin, 'users'=> $users, 'modules'=>$modules, 'stats'=>$user_stats]);
    }

    protected function userSearch($query, $search, $filter = null) {
        $admin = $this->getAuth();
        $query = User::join('user_activities', 'users.id', '=', 'user_activities.user_id')
            ->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('states', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%')
                ->orWhere('companies', 'LIKE', '%' . $search . '%');
            });

        $result = $query;

        if ($filter === 'certified') {
            $result = $query->whereNotNull('cert')->whereNotNull('training_done');
        }
        elseif ($filter === 'finished') {
            $result = $query->whereNull('cert')->whereNotNull('training_done');
        }
        elseif ($filter === 'unfinished') {
            $result = $query->whereNull('cert')->whereNull('training_done');
        }
        elseif ($filter === 'rsm') {
            $result = $query->where('admin_id', $admin->id);
        }

        return $result;
    }

    protected function userJoin($filter)
    {
        $admin = $this->getAuth();
        $query = User::join('user_activities', 'users.id', '=', 'user_activities.user_id');
        if ($filter === 'certified') {
            $query = $query->whereNotNull('cert')->whereNotNull('training_done');
        }
        elseif ($filter === 'finished') {
            $query = $query->whereNull('cert')->whereNotNull('training_done');
        }
        elseif ($filter === 'rsm') {
            $query = $query->where('admin_id', $admin->id);
        }
        elseif ($filter === 'unfinished') {
            $query = $query->whereNull('cert')->whereNull('training_done');
        }
        return $query;
    }

    public function userDetail($id) {
        session(['user' => $id]);
        session()->forget('step');
        $admin = auth()->guard('admin')->user();
        $user = User::where('id', $id)->first();
        $activity = UserActivity::where('user_id', $id)->first();
        $modules = Module::all();
        $messages = Message::where('user_id', $id)->get();
        $rsm = null;

        if ( !is_null( $user->admin_id ) ) {
            $rsm = Admin::where('id', $user->admin_id)->first();
        }

        // get all answers from user with Trait QuizREsults
        $qNa = $this->getQuizResults();

        // set modules total for check on if last module quiz is completed
        $m_count = count($modules);
        $mod_last = 'module_' . sprintf('%02d', $m_count);

        return view('admin.user')->with(
            [
                'admin'     => $admin,
                'rsm'       => $rsm,
                'user'      => $user,
                'activity'  => $activity,
                'modules'   => $modules,
                'messages'  => $messages,
                'answers'   => $qNa['a'],
                'questions' => $qNa['q'],
                'q_tot'     => $qNa['t'],
                'mod_last'  => $mod_last,
                'm_count'   => $m_count,
            ]
        );
    }

    public function userDetailStep($id, $step) {
        session(['step' => $step]);
        $admin    = auth()->guard('admin')->user();
        $user     = User::find(session()->get('user'));
        $activity = UserActivity::where('user_id', session()->get('user') )->first();
        $module   = Module::where('id', $step)->first();
        $m_count  = Module::count();
        $docs     = InstallImage::where('user_id', session()->get('user') )->get();
        $notes    = Note::where('user_id', session()->get('user') )->where('module_id', $step)->where('admin_id', $admin->id)->first();
        $msgs     = Message::where('user_id', session()->get('user'))->get();

        return view('admin.step', ['admin'=>$admin, 'user'=>$user, 'module'=> $module, 'm_count' => $m_count, 'activity' => $activity, 'docs'=>$docs, 'notes'=>$notes, 'msgs'=>$msgs]);
    }

    public function userDetailPost(Request $request) {
        $user = User::where('id', $request->session()->get('user', 'default'))->first();
        $m_count = Module::count();

        $review_date = null;

        $mod_name = 'module_' . sprintf("%02d", htmlentities($request->session()->get('step', 'default') ) );

        if ( !empty( $request->review_03_date ) ) {
            $new_datetime = $request->review_03_date . ' ' . $request->review_03_time;
            $review_date = date("Y-m-d H:i:s", strtotime($new_datetime) );
        }
        elseif ( $request->review_06_date !== null) {
            $new_datetime = $request->review_06_date . ' ' . $request->review_06_time;
            $review_date = date("Y-m-d H:i:s", strtotime($new_datetime) );
        }
        elseif ( $request->session()->get('step') == $m_count) {

            // create cert# with first and last initials + their user db id
            $u_id = $user->id;
            $fi = substr($user->first_name, 0, 1);
            $li = substr($user->last_name, 0, 1);

            // just in case fi and li don't get populated
            $rstring = 'abcdefghijklmnopqrstuvwxyz';

            if ($fi == '') {
                $fi = $rstring[rand(0, strlen($rstring)-1)];
            }

            if ( $li == '' ) {
                $li = $rstring[rand(0, strlen($rstring)-1)];
            }

            $cert = bin2hex( $fi . $li . $u_id);

            $cert_date = date('Y-m-d H:i:s');

            User::where('id', $user->id)
                ->update([
                    'cert' => $cert,
                    'cert_date' => $cert_date
                ]);

            $cert_pull = User::where('id', $user->id)->value('cert');

            // send notification to user that they have been certified
            $user->notify(new Step3([
                'subject'  => 'Lion Energy Certification',
                'greeting' => 'Congratulations, ' . $user->first_name,
                'intro'    => '',
                'message'  => 'Lion Energy has confirmed the completion of your training and issued you a certification number. This allows you to install the Lion Sanctuary system.',
                'outtro'   => '<strong>Certification number: ' . $cert_pull . '</strong>',
                'url'      => route('home'),
                'url_display' => 'View Dashboard',
            ]));

            // send notification to RSM that they have been certified
            $state = DB::table('usa_states')->where('abbrev', strtoupper($user->states))->orWhere('name', ucwords($user->states))->first();

            if ($state) {
                $rsm = RegionalRep::where('id', $state->rep)->first();

                if ( $rsm ) {
                    $rsm->notify(new RSM([
                        'subject' => 'Lion Energy Certification',
                        'intro'   => '<p>The following person is now a certified installer: </p><ul><li>' . $user->first_name . ' ' . $user->last_name . '</li><li>Company: ' . $user->companies . '</li><li>Email: ' . $user->email  .'</li><li>Phone: ' . $user->phone . '</li><li>State:  ' . ucwords($state->name) . '</li></ul>',
                        'message' => '<p><strong>Certification number: ' . $cert_pull . '</strong></p>',
                    ]));        }
                }
            }

        return redirect()->route('userDetail', ['id'=>$request->session()->get('user')]);
    }

//    public function step3date(Request $request) {
//        $r_num = 'review_03';
//        $r_date = $r_num . '_date';
//        $r_time = $r_num . '_time';
//
//        $new_datetime = htmlentities($request->$r_date) . ' ' . htmlentities($request->$r_time);
//
//        $review_date = date("Y-m-d H:i:s", strtotime($new_datetime) );
//
//        $update_data = [
//            $r_num => $review_date,
//            'review_03_user_request' => null
//        ];
//
//        if ( UserActivity::where('user_id', $request->session()->get('user'))->update(
//            $update_data
//        )
//        ) {
//            Session::flash('success' . $request->session()->get('step'),'User information updated.');
//        }
//        else {
//            Session::flash('error' . $request->session()->get('step'),'User information could not be updated.');
//        }
//
//        // Send notification to user
//        $user = User::where('id', $request->session()->get('user'))->first();
//
//        $notify_data = [
//            'subject' => 'Appointment Set',
//            'intro'   => 'Lion Energy has set an appointment with you for the following date and time',
//            'message' => date('M d, Y @ h:i A', strtotime( $new_datetime ) ),
//            'outtro'  => 'Make sure all documents on Step 4 are uploaded before this date.',
//            'url'     => secure_url( route('home' ) ),
//        ];
//
//        $user->notify( new Step3($notify_data) );
//
//        return redirect()->back();
//    }
//
//    public function step6Date(Request $request) {
//        $r_num  = 'review_06';
//        $r_date = $r_num . '_date';
//        $r_time = $r_num . '_time';
//
//        $new_datetime = htmlentities($request->$r_date) . ' ' . htmlentities($request->$r_time);
//
//        $review_date = date("Y-m-d H:i:s", strtotime($new_datetime) );
//
//        $update_data = [
//            'review_06_admin_request' => $review_date,
//            'review_06_user_request'  => null
//        ];
//
//        if ( UserActivity::where('user_id', $request->session()->get('user'))->update(
//            $update_data
//        )
//        ) {
//            Session::flash('success6','User information updated.');
//        }
//        else {
//            Session::flash('error6','User information could not be updated.');
//        }
//
//        // Send notification to user
//        $user = User::where('id', $request->session()->get('user'))->first();
//
//        $notify_data = [
//            'subject' => 'Final Inspection Change',
//            'intro'   => 'Lion Energy has suggested change to your appointment for the <strong>final inspection</strong>.',
//            'message' => date('M d, Y @ h:i A', strtotime( $new_datetime ) ),
//            'outtro'  => 'Click below to review this appointment.',
//            'url'     => secure_url( route('modules',['id' => $request->session()->get('user')] ) ),
//        ];
//
//        $user->notify( new Step3($notify_data) );
//
//        return redirect()->back();
//    }
//
//    public function finalInspectDate(Request $request) {
//        $user =  $request->session()->get('user', 'default');
//
//        $review_date = date("Y-m-d", strtotime($request->review_06_date) );
//        $review_time = date('H:i:s', strtotime($request->review_06_time) );
//
//        $review_date_time = $review_date . ' ' . $review_time;
//
//        if ( UserActivity::where('user_id', $user)->update(
//            [
//                'review_06' => $review_date_time,
//                'review_06_user_request' => null
//            ])
//        ) {
//            Session::flash('success','On-site inspection date updated.');
//        }
//        else {
//            Session::flash('error','Inspection date could not be updated. Contact tech support for help.');
//        }
//
//        $notify_data = [
//            'subject' => 'Appointment Set',
//            'intro'   => 'Lion Energy has set an appointment with you for the <strong>final inspection</strong> of your installation for the following date and time.',
//            'message' => date('M d, Y @ h:i A', strtotime( $review_date_time ) ),
//            'outtro'  => 'Make sure the installation is fully complete before this date.',
//            'url'     => secure_url( route('home' ) ),
//        ];
//
//        $user_data = User::where('id', $user)->first();
//
//        $user_data->notify( new Step3($notify_data) );
//
//        return redirect()->route('userDetail', ['id'=>$user]);
//    }

    public function adminCreate() {
        $admin    = auth()->guard('admin')->user();
        if ( $admin->super_admin == 1) {
            return view( 'admin.create')->with(['admin' => $admin]);;
        }
        else {
            Session::flash('error', 'You don\'t have the right privileges to create a new admin.' );
            return redirect()->route('admin-list');
        }
    }

    public function adminCreateProcess(Request $request) {
        $validate_fields = [
            'first_name' =>['required','string','max:255'],
            'last_name' =>['required','string','max:255'],
            'email'=>['required','email','unique:admins','max:255'],
            'role'=>['required'],
        ];

        $validate_messages = [
            'first_name.required' => 'First name is required.',
            'last_name.required'  => 'Last Name is required',
            'email.required'      => 'A valid email address is required.',
            'email.unique'        => 'The email address is already assigned to an Admin.',
            'role.required'       => 'You must select a role for the Admin.',
        ];

        $request->validate( $validate_fields, $validate_messages );

        $add_fields = [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
        ];

        if ( $request->role == 1 ) {
            $add_fields['super_admin'] = 1;
        }
        elseif ( $request->role == 2 ) {
            $admin = Admin::orderBy('rsm', 'desc')->first();
            $new_order = $admin->rsm + 1;
            $add_fields['rsm'] = $new_order;
        }

        if ( Admin::create($add_fields) ) {

            $data = new AdminReset;
            $token = Hash::make(rand(24,24));

            // upsert updates an existing record or inserts a new one
            // https://laravel.com/docs/10.x/queries#upserts
            $data->upsert([
                [
                    'email' => $request->email,
                    'token' => $token
                ]
            ],
                ['email'],
                ['token']
            );

            $url = config('app.url');
            $link = $url . '/admin/reset-password?token=' . $token . '&email=' . $request->email;

            $notify_data = [
                'subject' => 'New User Account',
                'intro'   => 'Account Creation',
                'message' => 'Lion Energy has created an administrator account for you. Click the link to create your password and sign in.',
                'outtro'  => '',
                'url'     => secure_url( $link ),
                'url_display' => 'View Dashboard',
            ];

            $admin_user = Admin::latest()->first();

            $admin_user->notify( new Step3($notify_data) );

            Session::flash('success', 'New admin account created successfully.');
        }
        else {
            Session::flash('error','There was an error creating the Admin in the database.');
        }

        return redirect()->route('admin-list');
    }

    function admin_msg(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required',
            'admin_id' => 'required',
            'message'    => 'required'
        ]);
        $data = new Message;
        $data->user_id = htmlentities($request->user_id);
        $data->admin_id = htmlentities($request->admin_id);
        $data->message = htmlentities($request->message);

        $data->save();

        // Send notification to user
        $user = User::find($data->user_id);

        $notify_data = [
            'intro' => 'Lion Energy has added a note on your certification board.',
            'message' => new HtmlString($data->message),
            'url' => route('home' ),
        ];

        $user->notify( new UserNote($notify_data) );

        $route = ( $request->module_id == null ) ? 'userDetail' : 'userDetailStep';

        return redirect()->route($route, ['id'=>$request->user_id, 'step'=>$request->module_id]);
    }

    function adminModuleNote(Request $request) {
        $validate = $request->validate([
            'note' => 'required',
            'user_id' => 'required',
            'admin_id' => 'required',
            'module_id' => 'required',
        ]);

        $note = html_entity_decode($request->note);

        $note = htmlentities($note);

        Note::updateOrCreate(
            [ 'user_id' => $request->user_id, 'module_id' => $request->module_id, 'admin_id' => $request->admin_id ],
            [ 'note' => $note ]
        );

        return redirect()->back();
    }

    function passwordForgot() {
        return view('admin.forgot');
    }

    function passwordForgotProcess(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns'
        ]);

        // Send email with password reset link
        $user = Admin::where('email', $request->email)->first();

        if (!is_null($user)) {
            $data = new AdminReset;
            $token = Hash::make(rand(24,24));

            // upsert updates an existing record or inserts a new one
            // https://laravel.com/docs/9.x/queries#upserts
            $data->upsert([
                [
                    'email' => $request->email,
                    'token' => $token
                ]
            ],
                ['email'],
                ['token']
            );


            $url = config('app.url');

            $link = $url . '/admin/reset-password?token=' . $token . '&email=' . $request->email;
            $notify_data = [
                'intro' => 'You have requested to have your admin password reset for https://certification.lionenergy.com',
                'url' => $link,
            ];

            $user->notify( new AdminPasswordReset($notify_data) );
        }

        Session::flash('status', 'Thank you. If your email was found in our system, your password reset link has been sent.');
        return redirect()->back();
    }

    function passwordReset() {
        return view('admin.reset');
    }

    function passwordResetProcess(Request $request) {
        $validation_fields = [
            'token' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'required | confirmed | min:12',
        ];

        $validation_messages = [
            'token.required'     => 'Missing validation token. Please use the link supplied in the "Forgot Password" email.',
            'email.required'     => 'Email missing. Please use the link supplied in the "Forgot Password" email',
            'password.confirmed' => 'The password fields must match.',
            'password.length'    => 'The password must be at least 12 characters long.',
        ];

        $validate = $request->validate( $validation_fields, $validation_messages );

        // Check that the reset token exists and hasn't expired. Token should be valid for 24 hours.
        $token = AdminReset::where('email', $request->email)->where('token', $request->token)->first();

        if ( $token === null ) {
            Session::flash('error', 'The email/token combination is invalid. Please resubmit a password reset request.');
            return redirect( route( 'admin-forgot' ) );
        }

        // get time difference between now and when token was created
        $token_updated = strtotime($token->updated_at);
        $current_time  = strtotime( date( now() ) );
        $time_difference = $current_time - $token_updated;

        if ( $time_difference > 86400 ) {
            Session::flash('error','The password request has expired. Please resubmit the password change request.');
            return redirect( route( 'admin-forgot' ) );
        }

        $password = Hash::make($request->password);

        $update = Admin::where('email', $request->email)
            ->update(
                [
                'password' => $password,
                ]
            );

        if ( $update == 1 ) {
            // remove token field
            AdminReset::where('email', $request->email)->delete();

            Session::flash('status', 'Your password has successfully been updated. Please login with your new information.');
        }
        else {
            Session::flash('status', 'Your password could not be updated. Please contact Lion Energy for assistance.');
        }

        return redirect( route('adminLogin') );
    }

    function adminUpdate(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $validation_fields = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc,dns',
        ];

        $validation_messages = [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email address is required',
            'email.email' => 'Must be a valid email address.',
        ];

        // Check if password fields used
        if (!empty($request->password) || !empty($request->password_confirmation)) {
            $validation_fields['password'] = 'confirmed | min:12';

            $validation_messages['password.confirmed'] = 'The password fields must match.';
            $validation_messages['password.length'] = 'The password must be at least 12 characters long.';
        }

        $validate = $request->validate($validation_fields, $validation_messages);

        // If password passes, hash it for saving
        if ( $request->password ) {
            $password = Hash::make($request->password);
        }

        // If optional fields of super_admin and rsm are filled out
        if ( $request->super_admin ) {
            // check if admin has super_admin rights themselves.
            if ( $admin->super_admin === NULL ) {
                Session::flash('error','You do not have the clearance to change the user\'s credentials.');

                return redirect( route('admin-list') );
            }
        }

        $update_fields = [
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
        ];

        // set for RSM only if it appears
        if ( $request->rsm ) {
            if ( $admin->super_admin === NULL ) {
                Session::flash('error','You do not have the clearance to change the user\'s credentials.');

                return redirect( route('admin-list') );
            }

            $update_fields['rsm'] = $request->rsm;
        }

        // set for Super Admin only if it appears
        if ( $request->super_admin ) {
            if ( $admin->super_admin === null ) {
                Session::flash('error','You do not have the clearance to change the user\'s credentials.');
                return redirect( route('admin-list') );
            }
            elseif ( $admin->email == $request->email ) {
                Session::flash('error','You cannot change your own credential level.');
                return redirect( route('admin-list') );
            }

            $update_fields['super_admin'] = $request->super_admin;
        }

        // set for Password only if it appears and validates
        if ( isset( $password ) ) {
            $update_fields['password'] = $password;
        }

        $data = Admin::where('email', $request->email )
                ->update( $update_fields);

        // remove the token/email combo from the admin_resets db table
        AdminReset::where('email', $request->email)->delete();

        Session::flash('password_status','The user has been updated.');
        return redirect( route('admin-list'));
    }

    function adminList() {
        $admin = auth()->guard('admin')->user();
        $list = Admin::all();

        return view('admin.admins-view')->with(['admin'=>$admin, 'list'=>$list]);
    }

    function adminIndividual($id) {
        $admin = auth()->guard('admin')->user();
        $user = Admin::where('id', $id)->first();

        return view('admin.admins-individual')->with(['admin'=>$admin, 'user'=>$user]);
    }

    function adminIndividualDelete(Request $request) {
        $user = Admin::where('id', $request->id)->delete();
        if ( $user == 1) {
            Session::flash('success','The user has been deleted.');
        }
        else {
            Session::flash('error','There was a problem deleting the user.');
        }

        return redirect(route('admin-list'));
    }

    function inviteInstaller() {
        $admin = auth()->guard('admin')->user();
        if (is_null($admin)) {
            return redirect()->route('adminLogin');
        }
        return view('admin.invite', ['admin' => $admin]);
    }

    function inviteInstallerProcess(Request $request) {
        $admin = auth()->guard('admin')->user();
        $validated = $request->validate([
            'email'      => 'required|email:rfc,dns',
            'first_name' => 'required',
            'last_name'  => 'required',
        ]);

        // Filter inputs
        $email      = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL);
        $first_name = filter_var($request->input('first_name'));
        $last_name  = filter_var($request->input('last_name'));

        // Check for user in current installer list
        $check_email = User::firstWhere('email', $email);


        if ( !is_null($check_email) ) {
            $request->session()->flash('duplicate','Email already exists in the system.');
            return redirect()->route('invite-installer', ['admin'=> $admin]);
        }

        // Check for current invitation
        $invite_check = UserInvite::firstWhere('email', $email);

        if ( is_null($invite_check) ) {
            $user = new UserInvite;

            $user->admin_id     = $admin->id;
            $user->first_name   = $first_name;
            $user->last_name    = $last_name;
            $user->email        = $email;
            $user->token        = bin2hex(random_bytes(32));

            $user->save();
            $msg = $first_name . ' ' . $last_name . ' has been added and an email invitation has been sent.';
        }
        else {
            $user = $invite_check;
            $msg = 'A reminder email has been sent to ' . $first_name . ' ' . $last_name . ' of your invitation to certify.';
        }

        $link = secure_url('/invite-accept?token=' . $user->token . '&email=' . $user->email);

        $notify_data = [
            'subject' => 'Invitation to Certify',
            'greeting' => 'Hello ' . $first_name . ',',
            'message' => 'Lion Energy has invited you to certify as an installer for their Sanctuary home battery backup system.',
            'url'     => $link,
            'url_display' => 'Register Now',
        ];

        $user->notify( new Generic($notify_data) );

        $request->session()->flash('status', $msg);

        return redirect()->route('invite-installer', ['admin'=> $admin]);
    }

}
