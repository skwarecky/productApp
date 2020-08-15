<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ResponseController as ResponseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends Controller
{
    /*
    Register API
    */
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return ResponseController::sendError('Validation Error.', $validator->errors());
        }
        if(User::where('email', $request->email)->first()){
            return ResponseController::sendError('User exists.', 'Use diffrent email.');
        }
        else{
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] = $user->createToken('productApp')->accessToken;
            $success['name'] = $user->name;
    
            return ResponseController::sendResponse($success, 'User register successfully');
        }
    }
}
