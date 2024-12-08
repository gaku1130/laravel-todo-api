<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Exceptions\CustomException;

class LoginController extends BaseController {
  /**
   * Login api
   *
   * @return \Illuminate\Http\Response
   */
  public function login(Request $request): JsonResponse {
    if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
      throw new CustomException('Request Parameter Error', "The provided email or password is incorrect. Please check your credentials and try again", 400);
    }

    $user = Auth::user();
    $data['token'] = $user->createToken('API Access')->plainTextToken;
    $data['userId'] = $user->id;

    return $this->sendResponse('data', $data, 200, 'User Login Successfully');
  }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request): JsonResponse {
      $request->user()->currentAccessToken()->delete();
  
      return $this->sendResponse(null, null, 200, 'successfully logout');
    }
  
     /**
   * Register api
   *
   * @return \Illuminate\Http\Response
   */
  public function register(Request $request): JsonResponse {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'password' => 'required',
      'confirmPassword' => 'required|same:password'
    ]);

    if ($validator->fails()) {
      throw new CustomException('Validation Error', $validator->errors(), 400);
    }

    $input = $request->all();
    $input['password'] = bcrypt($input['password']);
    $user = User::create($input);
    $data['accessToken'] = $user->createToken('API Access')->plainTextToken;
    $data['userId'] = $user->id;

    return $this->sendResponse('data', $data, 201, 'User Registeration successfully');
  }
}