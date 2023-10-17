<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Auth;

class RsmController extends Controller
{
    public function RsmMap() {
        $admin = auth()->guard('admin')->user();
        return view('admin.rsm.map')->with(['admin'=>$admin]);
    }

    public function RsmMapSubmit(Request $request) {
        $validated = $request->validate([
           'region' => ['required | integer'],
           'states' => ['required'],
        ]);

        return view('/');
    }
}
