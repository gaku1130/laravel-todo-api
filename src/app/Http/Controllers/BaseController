<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller {
  /**
   * success response method.
   *
   * @return \Illuminate\Http\Response
   */
  public function sendResponse($keyName, $result, $status, $message = null) {
    $response = [
      'status' => $status
    ];

    if (isset($keyName) && isset($result)) {
      $response[$keyName] = $result;
    }

    if (isset($message)) {
      $response['message'] = $message;
    }

    return response()->json($response, $status);
  }

  /**
  * return error response.
  *
  * @return \Illuminate\Http\Response
  */
  public function sendError($error, $errorMessages = [], $status = 404) {
    $response = [
      'error' => [
        'status' => $status,
        'message' => $error,
        'details' => !empty($errorMessages) ? $errorMessages : null,
      ]
    ];

    return response()->json($response, $status);
  }
}