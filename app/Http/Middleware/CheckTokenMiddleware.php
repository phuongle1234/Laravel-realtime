<?php

namespace App\Http\Middleware;

use Closure;
use \Illuminate\Http\Request;
use App\Traits\RedisStorage;
use App\Services\VerifyUserService;

class CheckTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function __construct(VerifyUserService $_service)
    {
      $this->_service = $_service;
    }

    public function handle(Request $request, Closure $next)
    {
        //$_token = $request->route('token');

        $_user = $this->_service->checkToken();

        if($_user){
            session(['verify_user' => $_user]);
            return $next($request);
        }

        return abort(404);
    }
}
