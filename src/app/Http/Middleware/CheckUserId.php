<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\CustomException;

class CheckUserId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
			$userId = $request->route('userId');

			if (!$userId) {
				throw new CustomException('Request Parameter Error', ['userId' => ['userId is required']], 400);
			}

			// 数字かどうかを判定
			if (!is_numeric($userId)) {
				throw new CustomException('Request Parameter Error', ['userId' => ['userId must be number']], 400);
			}

			// リクエストパラメーターのuserIdの値が認証されたログインしているユーザーのidの値と同じかどうかを確認
			if ($userId != Auth::id()) {
				throw new CustomException('Forbidden', ['userId' => ['This action is not authorized for the specified user']], 403);
			}

			// 条件を満たす場合、次の処理に進む
			return $next($request);
    }
}