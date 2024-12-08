<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\CustomException;

class CheckTodoId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
			$todoId = $request->route('todoId');

			if (!$todoId) {
				throw new CustomException('Request Parameter Error', ['todoId' => ['todoId is required']], 400);
			}

			// 数値かどうかを判定
			if (!is_numeric($todoId)) {
				throw new CustomException('Request Parameter Error', ['todoId' => ['todoId must be number']], 400);
			}

			return $next($request);
    }
}