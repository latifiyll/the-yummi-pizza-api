<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user'=>$user,'acess_token'=>$accessToken]);

    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if(!auth()->attempt($loginData)){
            return response(['message'=>'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }

    function changePassword(Request $request) {
        $data = $request->all();
        $user = Auth::guard('api')->user();
   
        //Changing the password only if is different of null
        if( isset($data['oldPassword']) && !empty($data['oldPassword']) && $data['oldPassword'] !== "" && $data['oldPassword'] !=='undefined') {
            //checking the old password first
            $check  = Auth::guard('web')->attempt([
                'email' => $user->email,
                'password' => $data['oldPassword']
            ]);
            if($check && isset($data['newPassword']) && !empty($data['newPassword']) && $data['newPassword'] !== "" && $data['newPassword'] !=='undefined') {
                $user->password = bcrypt($data['newPassword']);
                // $user->isFirstTime = false; //variable created by me to know if is the dummy password or generated by user.
                $user->token()->revoke();
                $token = $user->createToken('newToken')->accessToken;
   
                //Changing the type
                $user->save();
   
                return json_encode(array('token' => $token)); //sending the new token
            }
            else {
                return "Wrong password information";
            }
        }
        return "Wrong password information";
    }

    public function logout(){   
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['success' =>'logout_success'],200); 
        }else{
            return response()->json(['error' =>'api.something_went_wrong'], 500);
        }
    }
 
}
