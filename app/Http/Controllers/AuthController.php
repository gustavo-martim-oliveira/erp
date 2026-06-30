<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signin()
    {
        if(auth()->check()){
            return redirect()->route('central.dashboard');
        }

        return view('auth.signin');
    }

    public function doSignin(Request $request){

        if(auth()->check()){
            return redirect()->route('central.dashboard');
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            flash()->error($validator->errors()->first());
            return redirect()->back()->withInput();
        }

        $remember = $request->has('remember') ? true : false;

        if(auth()->attempt($request->only('email', 'password'), $remember)){
            
            if(!auth()->user()->is_active || !auth()->user()->is_active == 0    ){
                auth()->logout();
                flash()->error('Seu usuário está inativo, entre em contato com o administrador!');
                return redirect()->back()->withInput();
            }

            flash()->success('Login realizado com sucesso!');
            return redirect()->route('central.dashboard');
        }else{
            flash()->error('Credenciais inválidas!');
            return redirect()->back()->withInput();
        }

    }

    public function signout()
    {
        auth()->logout();
        flash()->success('Logout realizado com sucesso!');
        return redirect()->route('sign');
    }
}
