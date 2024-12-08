<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Exceptions\CustomException;

class FindUser
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		// userIdに対応するユーザーが存在しない場合は404エラーを返す
		$user = User::find($request->userId);
		if (!$user) {
			throw new CustomException('Not Found', ['user data nothing'], 404);
		}

		return $next($request);
	}
}