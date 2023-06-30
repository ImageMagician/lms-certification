<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserActivity;
use App\Rules\PhoneValidation;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'     => ['required', 'min:10', new PhoneValidation],
            'companies' => ['required', 'string'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Format the phone number as (XXX) XXX-XXXX

        // remove everything except numbers
        $phone = preg_replace("/[^0-9]/", '', $data['phone']);

        // check if they added the "1" to the start of the number. If so, remove it.
        if ( strlen( $phone ) == 11 && $phone[0] == 1 ) {
            $phone = substr( $phone, 1);
        }

        // Add the dash first for the proper position, then work to the end paren and space, and then the starting paren.
        $phone = substr_replace($phone, '-', 6, 0);
        $phone = substr_replace($phone, ') ', 3, 0);
        $phone = substr_replace($phone, '(', 0, 0);

        $user_id = User::insertGetId([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $phone,
            'companies' => $data['companies'],
            'password'  => Hash::make($data['password']),
        ]);

        // Create a row in the user_activity table for the user
        UserActivity::create([
            'user_id' => $user_id,
        ]);

        $new_user = User::find($user_id);

        return $new_user;
    }
}
