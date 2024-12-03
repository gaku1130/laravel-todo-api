<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Exceptions\CustomException;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
	{
		if (!$request->expectsJson()) {
			return route('login');
		}
	}

	public function handle($request, \Closure $next, ...$guards) {
		$authHeader = $request->header('Authorization');
		if (!$authHeader || !Str::startsWith($authHeader, 'Bearer ')) {
			throw new CustomException('Unauthorized', ['accessToken' => ['Access token is required but not provided']], 401);
		}

		$token = Str::after($authHeader, 'Bearer ');

		$tokenRecord = DB::table('personal_access_tokens')->where('token', hash('sha256', $token))->first();

		if (!$tokenRecord) {
			throw new CustomException('Unauthorized', ['accessToken' => ['Token not found in database']], 401);
		}

		$user = User::find($tokenRecord->tokenable_id);

		if (!$user) {
			throw new CustomException('Not Found', ['user' => ['user not found']], 404);
		}

		return parent::handle($request, $next, ...$guards);
	}
}
