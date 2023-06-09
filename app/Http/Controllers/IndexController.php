<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class IndexController extends Controller
{
    function index() {
        $user = Auth::user();

        if ( $user !== null) {
            return redirect()->route('home');
        }
        else {
            return view('index');
        }
    }
}
