<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\CustomException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TodoController extends Controller
{
    private $todo;

    public function __construct(Todo $todo)
    {
      $this->todo = $todo;
    }
    
    private $userId;

    private function authorizeUser($userId)
    {
        if (Auth::id() != $userId) {
            throw new CustomException(
                'Forbidden',
                ['details' => 'You do not have permission to access this todo resource.'],
                403
            );
        }
    }


    public function index(Request $request, $userId)
    {
        try {
            $this->authorizeUser($userId);

            $todos = $this->todo->where('user_id', $userId)->get(['id', 'title', 'completed']);

            return response()->json($todos, 200);
        } catch (\Exception $e) {
            \Log::error('Todo Index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            throw new CustomException(
                'Server Error',
                ['details' => 'An unexpected error occurred on the server. Please try again later or contact support if the issue persists.'],
                500
            );
        }
    }


    public function create(Request $request, $userId)
    {
        try {
            $this->authorizeUser($userId); 

            $validated = $request->validate([
                'content' => 'required|string|max:255',
            ]);

            $input = $request->all();
            $input['user_id'] = $request->userId;
            $input['complated'] = false;
            $todo = $this->todo->create($input);
        
            return response()->json([
                'message' => 'Successful create user todo',
            ], 201);
        } catch (ValidationException $e) {
            throw new CustomException('Request Parameter Error',['details' => "The 'email' field must be a valid email address."], 400);
        } catch (\Exception $e) {
            throw new CustomException('Server Error', ['details' => 'An unexpected error occurred.'], 500);
        }
    }


    public function show(Request $request, $userId, $todoId)
    {
        try {
            $this->authorizeUser($userId);

            $todo = Todo::where('user_id', $userId)->findOrFail($todoId);

            return response()->json($todo, 200);
        } catch (ModelNotFoundException $e) {
            throw new CustomException('Not Found', ['details' => 'The requested todo resource was not found on the server.'], 404);
        } catch (\Exception $e) {
            throw new CustomException('Server Error', ['details' => 'An unexpected error occurred.'], 500);
        }
    }

    public function update(Request $request, $userId, $todoId)
    {
        try {
            $this->authorizeUser($userId);

            $validated = $request->validate([
                'content' => 'required|string|max:255',
            ]);

            $todo = $request->todo;
            $input = $request->all();
            $todo->content = $input['content'];
            if (isset($input['complated']) && $input['complated'] == true) {
              $todo->complated = true;      
            }

            $todo->save();

            $newTodo = $this->todo->find($todo->id);
            return response()->json($todo, 200);
        } catch (ValidationException $e) {
            throw new CustomException('Request Parameter Error', $e->errors(), 400);
        } catch (ModelNotFoundException $e) {
            throw new CustomException('Not Found', ['details' => 'The requested todo resource was not found on the server.'], 404);
        } catch (\Exception $e) {
            throw new CustomException('Server Error', ['details' => 'An unexpected error occurred.'], 500);
        }
    }


    public function delete(Request $request, $userId, $todoId)
    {
        try {
            $this->authorizeUser($userId);

            $todo = Todo::where('user_id', $userId)->findOrFail($todoId);
            $todo->delete();

            return response()->json([
                'message' => 'Successfully deleted',
            ], 200);
        } catch (ModelNotFoundException $e) {
            throw new CustomException('Not Found', ['details' => 'The requested todo resource was not found on the server.'], 404);
        } catch (\Exception $e) {
            throw new CustomException('Server Error', ['details' => 'An unexpected error occurred.'], 500);
        }
    }
}
