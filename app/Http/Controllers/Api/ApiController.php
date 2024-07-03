<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class ApiController extends Controller
{
    public function login(Request $request){
        response()->json($request);
        try{
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ],401);
            }

            if(!Auth::attempt($request->only('email','password'))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email e Senha não estão corretos',
                ],401);
            }

            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'Usuario Logado',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ],200);


        }catch(Exception $e){

        }

    }
}
