<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Todo;
use App\Exceptions\CustomException;

class FindTodo
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		// todoIdに対応するユーザーが存在しない場合は404エラーを返す
		$todo = Todo::find($request->todoId);
		if (!$todo) {
			throw new CustomException('Not Found', ['todo' => ['todo data nothing']], 404);
		}

		$request->merge(['todo' => $todo]);

		return $next($request);
	}
}