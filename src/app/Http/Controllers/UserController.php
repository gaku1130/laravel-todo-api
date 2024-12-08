<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController {
  private $user;

  public function __construct(User $user)
  {
    $this->user = $user;
  }

  /**
   * find all users api
   *
   * @return \Illuminate\Http\Response
   */
  public function index(): JsonResponse
  {
    $users = $this->user->all();

    return $this->sendResponse('users', $users, 200);
  }

  /**
   * find user api
   *
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request): JsonResponse
  {
    return $this->sendResponse('user', $request->user(), 200);
  }
}