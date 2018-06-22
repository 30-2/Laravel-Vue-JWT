<?php

namespace App\Http\Controllers;
use App\User;
use Log;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class FrontEndUserController extends Controller
{
    public function signUp(Request $request)
{
    Log::debug("sign up");
    $user = User::create(['email' => $request->email, 'password' => bcrypt($request->password),'name' => $request->name]);
}
public function signIn(Request $request)
{
    try {
        if (! $token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    } catch (JWTException $e) {
        return response()->json(['error' => 'could_not_create_token'], 500);
    }

    return response()->json(compact('token'));
}
}
