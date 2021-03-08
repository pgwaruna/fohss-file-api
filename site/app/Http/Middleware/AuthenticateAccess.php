<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Config;

class AuthenticateAccess extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $allowedSecrets = explode(',', config::get("secrets.secret_keys",null));

        $allowedSecrets = explode(',', globalSetting('ALLOWED_SECRETS'),false);

        if(in_array($request->header('Authorization'), $allowedSecrets)) {
            return $next($request);
        }
        abort(Response::HTTP_UNAUTHORIZED);
    }
}
