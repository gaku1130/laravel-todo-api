<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index($userId)
    {
        try {
            if (Auth::id() != $userId) {
                return response()->json([
                    'error' => [
                        'code' => 403,
                        'message' => 'Forbidden',
                        'details' => 'You do not have permission to access this todo resource.',
                    ],
                ], 403);
            }

            $todos = Todo::where('user_id', $userId)->get(['id', 'title', 'completed']);

            return response()->json($todos, 200);
        } catch (\Exception $e) {
            \Log::error('Todo Index Error: ' . $e->getMessage(), [
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


    public function store(Request $request, $userId)
    {
        try {
            if (Auth::id() != $userId) {
                return response()->json([
                    'error' => [
                        'code' => 403,
                        'message' => 'Forbidden',
                        'details' => 'You do not have permission to create a todo for this user.',
                    ],
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'content' => 'required|string|max:255',
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

            $todo = Todo::create([
                'user_id' => $userId,
                'title' => $request->content, 
                'completed' => false, 
            ]);

            return response()->json([
                'message' => 'Successful create user todo',
                'todo' => [
                    'id' => $todo->id,
                    'title' => $todo->title,
                    'completed' => $todo->completed,
                ],
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Todo Store Error: ' . $e->getMessage(), [
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
