<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    //
    public function register(Request $request)  {
        $validatedData = $request->validate([
            'name'=>['required','string','max:255'],
            'email'=>['required','string','email','max:255','unique:users'],
            'password'=>['required','string','min:8','max:20'],
        ] );

        $user = User::create(attributes: [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make(value: $validatedData['password']),
        ]);

        $token = $user ->createToken('auth_token')->plainTextToken;

        return response() ->json(data: [
            "success" => true,
            "errors" => [
                "code" =>0,
                "msg" =>""
            ],
            "data" => [
                "access_token" =>$token,
                "token-type" => "Bearer"
            ],
            "msg"=>"Usuario creado satisfactoriamente",
            "count" =>1
        ]);
    }

    public funtion login (Request $request) {
        if(!Auth::attempt($request->only("email","password"))){
            return response()->json([
                "success"=> false,
                "errors"=> [
                    "code" =>401,
                ],
                "data"=> "",
                "count"=> 0
            ], 401);
        }
        $user = User::where("email", $request->email)->firstOrFail();
        $token = $user -> createToken("auth_token")->plainTextToken;

        return response() ->json(data: [
            "success"=> true,
            "errors"=> [
                "code"=> 200,
                "msg"=> ""
                ],
                "data"=> [
                    "acess token"=> $token,
                    "token_type"=> "Bearer"
                "msg"=>"Ha iniciado secion correctamente",
                "count"=> 1
        ],200);
    }


    public function me(Request $request) {
        return response()->json( [
            "success"=> true,
            "errors"=> [
                "code"=> 200,
                "msg"=>""
                ],
                "data"=>$request->user(),
                "count"=> 1
        ], 200);
    }
}
