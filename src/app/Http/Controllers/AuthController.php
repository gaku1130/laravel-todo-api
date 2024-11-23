<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {




        
    }

    public function login(Request $request)
    {
        try{
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $token = Auth::user()->createToken('AccessToken')->plainTextToken;
                return response()->json([
                    'status' => 200,
                    'data' => [
                        'userId' => $user->id,
                        'accessToken' => $token,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'error' => [
                        'code' => 400,
                            'message' => 'Request Parameter Error',
                            'details' => 'The provided credentials are incorrect.',
                    ],
                ],400);
            }
        }catch(\Exception $e){
            return response()->json([
                'error' => [
                    'code' => 500,
                    'message' => 'Server Error',
                    'details' => 'An unexpected error occurred on the server. Please try again later or contact support if the issue persists.',
                ],
            ], 500);
        }
    }



    public function user(Request $request){
        return response()->json(
            [
                $request->user()->name,
                $request->user()->email,
            ]
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'ログアウトしました。'], 200);
    }
}