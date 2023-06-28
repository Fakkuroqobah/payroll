<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;

class AAdminController extends Controller
{
    public function viewLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $check = Admin::select(['username'])->where('username', $request->username)->first();

        if($check) {
            $guard = Auth::guard('admin');

            if(!$guard->attempt(['username' => $request->username, 'password' => $request->password])) {
                return redirect()->back()->with('danger', 'Username atau password salah');
            }
    
            if(Auth::guard('admin')->check()) {
                return redirect()->route('a_karyawan_index');
            }else{
                return redirect()->back()->with('danger', 'Username atau password salah');
            }
        }

        return redirect()->back()->with('danger', 'Username atau password salah');
    }

    public function logout() 
    {
        if(Auth::guard('admin')->check()) Auth::guard('admin')->logout();

        return redirect('/');
    }
}
