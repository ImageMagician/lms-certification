<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class IndexController extends Controller
{
    function index(Request $request) {
        if ( $request->query('ref') != null) {
            $request->session()->put('ref', $request->query('ref'));
        }

        $user = Auth::user();

        if ( $user !== null) {
            return redirect()->route('home');
        }
        else {
            return view('index');
        }
    }
}
