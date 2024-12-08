<?php

namespace App\Http\Controllers\API\V1\Todo;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Exceptions\CustomException;

class TodoController extends BaseController {
  private $todo;

  public function __construct(Todo $todo)
  {
    $this->todo = $todo;
  }

  /**
   * find all todos of user api
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request): JsonResponse
  {
    $todos = $this->todo->where('user_id', $request->userId)->get();
    
    return $this->sendResponse('todos', $todos, 200);
  }

  /**
   * find todo of user api
   *
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request): JsonResponse
  {
    return $this->sendResponse('todo', $request->todo, 200);
  }

  /**
   * create todo of user api
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'content' => 'required',
    ]);

    if ($validator->fails()) {
      throw new CustomException('Validation Error', $validator->errors(), 400);
    }

    $input = $request->all();
    $input['user_id'] = $request->userId;
    $input['complated'] = false;
    $todo = $this->todo->create($input);

    return $this->sendResponse(null, null, 201, 'Create user`s todo successfully');
  }

  /**
   * update todo of user api
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'content' => 'required',
    ]);

    if ($validator->fails()) {
      throw new CustomException('Validation Error.', $validator->errors(), 400);
    }

    $todo = $request->todo;
    $input = $request->all();
    $todo->content = $input['content'];
    if (isset($input['complated']) && $input['complated'] == true) {
      $todo->complated = true;      
    }
  
    $todo->save();

    $newTodo = $this->todo->find($todo->id);

    return $this->sendResponse('todo', $newTodo, 200);
  }

  /**
   * delete todo of user api
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request): JsonResponse
  {
    $request->todo->delete();

    return $this->sendResponse(null, null, 200, 'Delete user`s todo successfully');
  }
}