<?php
namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService{


    public function register($request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return $user;
    }

    public function login($request){
        $credendials = ['email'=>$request->email, 'password'=>$request->password];
        if(Auth::attempt($credendials)){
            $user = User::with('settings')->where('email', $request->email)->first();
            $token = $user->createToken('authToken')->plainTextToken;

            $data = [];
            $data['id'] = $user->id;
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['preference'] = $user->settings ? $user->settings->preference:null;
            return [
                'user' => $data,
                'token' => $token,
            ];
        }else{
            return false;

        }
    }

}
