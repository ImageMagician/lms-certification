<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\ApiLoginRequest;
use App\Models\ApiUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use App\Models\User;
use stdClass;

class ApiController extends Controller
{
    use HttpResponses;

    public function login(ApiLoginRequest $request) {
        $request->validated($request->all());
        $timestamp = date('Y-m-d H:i:s', strtotime($request->timestamp) );

        $user = APIUser::where('email', $request->email)->first();
        if ( $user != null ) {
            if ( !Hash::check( $request->password, $user->password ) ) {
                return response('Credentials do not match',403);
            }
            else {
                $u_array = array();
                $u_list =  User::whereNotNull('cert')->where('cert_date', '>', $timestamp)->get();
                $i = 0;
                foreach ( $u_list as $u ) {
                    $u_class = new stdClass();
                    $u_class->name = $u->name;
                    $u_class->phone = $u->phone;
                    $u_class->email = $u->email;
                    $u_class->cert = $u->cert;
                    $u_array[$i] = $u_class;
                    $i++;
                }
                $certs = new stdClass();
                $certs->timestamp = $timestamp;
                $certs->type = 'sanctuary';
                $certs->certifications = $u_array;

                return json_encode($certs);
            }
        }
        else {
            return response('no user found.', 403);
        }
    }

    public function verifySingle(Request $request) {
        $request->validate([
            'email' => ['required','string','email'],
            'password' => ['required','string','min:8'],
            'user_email' => ['required','string','email']
        ]);

        $user = APIUser::where('email', $request->email)->first();
        if ( $user != null ) {
            if ( !Hash::check( $request->password, $user->password ) ) {
                return response('Credentials do not match',403);
            }
            else {
                $u_list =  User::where('email', $request->user_email)->whereNotNull('cert')->first();
                if ( $u_list == null ) {
                    return response('no certification found.', 403);
                } else {
                    $u_class = new stdClass();
                    $u_class->name = $u_list->name;
                    $u_class->phone = $u_list->phone;
                    $u_class->email = $u_list->email;
                    $u_class->type = 'sanctuary';
                    $u_class->cert = $u_list->cert;
                    return json_encode($u_class);
                }
            }
        }
        else {
            return response('no user found.', 403);
        }
    }

    public function register(StoreUserRequest $request) {
        $request->validated($request->all());

        $user = ApiUser::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken,
        ]);
    }
    public function logout() {
        return response()->json('I\'m loggin out.');
    }

    public function index() {
        return 'hi';
//        return User::whereNotNull('cert')->get()->toJson();
    }
}
