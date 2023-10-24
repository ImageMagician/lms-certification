<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;

class RsmController extends Controller
{
    public function RsmMap() {
        $admin  = auth()->guard('admin')->user();
        $states = DB::table('usa_states')->get();
        $reps   = DB::table('admins')->where('rsm', '!=', null)->get();

        return view('admin.rsm.map')->with(['admin'=>$admin, 'state' => $states, 'rep' => $reps]);
    }

    public function RsmMapSubmit(Request $request) {
        $validated = $request->validate([
           'region' => ['required'],
           'states' => ['required'],
        ]);

        if ( $request->region == 0) {
            $region = null;
        }
        else {
            $region = htmlentities($request->region);
        }

        $states = explode(',', $request->states);
        foreach ( $states as $state) {
            $data = DB::table('usa_states')->where('abbrev', $state)->update(['rep'=> $region]);
        }

        return redirect()->back();
    }
}
