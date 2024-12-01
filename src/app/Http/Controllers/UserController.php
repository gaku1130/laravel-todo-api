<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function findUser(Request $request)
    {

        try {
            if ($request->user()->id != $userId) {
                return response()->json([
                    'error' => [
                        'code' => 403,
                        'message' => 'Forbidden',
                        'details' => 'You do not have permission to access this user resource.',
                    ],
                ], 403);
            }
    
            $user = User::find($userId);

            if (!$user) {
                return response()->json([
                    'error' => [
                        'code' => 404,
                        'message' => 'Not Found',
                        'details' => 'The requested user resource was not found on the server.',
                    ],
                ], 404);
            }

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Find User Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
    
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
