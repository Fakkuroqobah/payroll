<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use Auth;

class KKaryawanController extends Controller
{
    public function login(Request $request)
    {
        $check = Karyawan::select(['email'])->where('email', $request->email)->first();

        if($check) {
            $guard = Auth::guard('karyawan');

            if(!$guard->attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->back()->with('danger', 'Email atau password salah');
            }
    
            if(Auth::guard('karyawan')->check()) {
                return redirect()->route('k_absensi_index');
            }else{
                return redirect()->back()->with('danger', 'Email atau password salah');
            }
        }

        return redirect()->back()->with('danger', 'Email atau password salah');
    }

    public function logout() 
    {
        if(Auth::guard('karyawan')->check()) Auth::guard('karyawan')->logout();

        return redirect('/');
    }
}
