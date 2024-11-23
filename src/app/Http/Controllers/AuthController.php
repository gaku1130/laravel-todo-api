<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|same:confirmPassword',
                'confirmPassword' => 'required|string|min:8',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'error' => [
                        'code' => 400,
                        'message' => 'Request Parameter Error',
                        'details' => $validator->errors()->first(),
                    ],
                ], 400);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), 
            ]);
    
            $token = $user->createToken('AccessToken')->plainTextToken;
    
            return response()->json([
                'status' => 201,
                'data' => [
                    'userId' => $user->id,
                    'accessToken' => $token,
                ],
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Register Error: ' . $e->getMessage());
    
            return response()->json([
                'error' => [
                    'code' => 500,
                    'message' => 'Server Error',
                    'details' => 'An unexpected error occurred on the server. Please try again later or contact support if the issue persists.',
                ],
            ], 500);
        }
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
        try {
            if (!Auth::check()) {
                return response()->json([
                    'error' => [
                        'code' => 401,
                        'message' => 'Authorization Error',
                        'details' => 'User authentication failed. Please check your credentials and try again.',
                    ],
                ], 401);
            }

            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'successfully logout',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'code' => 500,
                    'message' => 'Server Error',
                    'details' => 'An unexpected error occurred on the server. Please try again later or contact support if the issue persists.',
                ],
            ], 500);
        }
    }
}