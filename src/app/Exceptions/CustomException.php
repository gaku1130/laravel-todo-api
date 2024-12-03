<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CustomException extends Exception
{
  protected $statusCode;
  protected $errorMessage;
  protected $errorMessages;

  public function __construct($message, $messages = [], $status = 404)
  {
    parent::__construct($message);
    $this->statusCode = $status;
    $this->errorMessage = $message;
    $this->errorMessages = $messages;
  }

  public function render(): JsonResponse
  {
    $errorResponse = [
      'error' => [
        'status' => $this->statusCode,
        'message' => $this->errorMessage,
      ]
    ];

    if (!empty($this->errorMessages)) {
      $errorResponse['error']['details'] = $this->errorMessages;
  }

    return response()->json($errorResponse, $this->statusCode);
  }
}