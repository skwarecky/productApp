<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ResponseController as ResponseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends Controller
{
    /*
    Login API 
    */

    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password'=> $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('sdnotes')->accessToken;
            $success['name'] = $user->name;

            return ResponseController::sendResponse($success, 'User login successfully');
        }
        else{
            return ResponseController::sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
}
