<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;

class RsmController extends Controller
{
    public function RsmMap() {
        $admin = auth()->guard('admin')->user();

        $states = DB::table('usa_states')->get();

        return view('admin.rsm.map')->with(['admin'=>$admin, 'state' => $states]);
    }

    public function RsmMapSubmit(Request $request) {
        $validated = $request->validate([
           'region' => ['required'],
           'states' => ['required'],
        ]);

        $states = explode(',', $request->states);

        foreach ( $states as $state) {
            $data = DB::table('usa_states')->update('rep', $request->region)->where('abbrev', $state);
        }

        return redirect()->back();
    }
}
