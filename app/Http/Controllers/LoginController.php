<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Lang, Validator;

class LoginController extends Controller
{
    public function gate(){
        $check = Auth::check();

        if ($check){
            return redirect()->route('dashboard.index');
        } else {
            return redirect()->route('login.index');
        }
    }

    public function index(){
        $check = Auth::check();

        if($check){
            return redirect()->route('dashboard.index');
        }
        else{
            return view ('page.login.index');
        }
    }

    public function post(Request $request){
        $messages = [
            'username.required' => Lang::get('web.login-username.required'),
            'password.required' => Lang::get('web.login-password.required'),
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], $messages);

        if($validator->fails())
        {
            $validator->errors()->add('login', Lang::get('web.login-failed'));
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if(Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->remember))
            return redirect()->route('dashboard.index');
        else
            return redirect()->back()->withInput()->withErrors(['login' => Lang::get('web.login-failed')]);
}

public function logout()
{
    Auth::logout();
    
    return redirect()->route('login.index');
}
}
