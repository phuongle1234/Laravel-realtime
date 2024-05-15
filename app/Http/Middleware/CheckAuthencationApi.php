<?php

namespace App\Http\Middleware;

use Closure;
use \Illuminate\Http\Request;
use App\Traits\RedisStorage;
use App\Services\VerifyUserService;

class CheckAuthencationApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    // public function __construct(VerifyUserService $_service)
    // {
    //   $this->_service = $_service;
    // }

    public function handle(Request $request, Closure $next)
    {
        $headers = apache_request_headers();

        if( $headers['Authentication'] != env('KEY_API') )
        return response()->json( "Authentication not found" ,400);

        return $next($request);
    }
}
