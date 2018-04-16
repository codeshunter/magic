<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

class LoginController extends Controller
{
    //
    public function login () {
        if($data=Input::all()){
            $user = new User();
            $admin=$user->first();
            if($admin->username!=$data['user']||Crypt::decrypt($admin->password)!=$data['pwd']){
                return back()->with('msg','用户名密码不正确');
            }
            session(['user'=>$admin]);
            return redirect('admin');
        }else{
            return view('admin.login');
        }
    }

    public function logout() {
        session(['user'=>null]);
        return redirect('admin/login');
    }
}
