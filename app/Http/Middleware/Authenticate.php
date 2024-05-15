<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Enums\EUser;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    protected function redirectTo($request)
    {
        // if want get $guards using code here

        if (! $request->expectsJson()) {

            $_role = str_replace( "auth:", "", $request->route()->getAction()['middleware'][1] );

            if( in_array(  $_role , [ EUser::STUDENT , EUser::TEACHER ])  )
              return route('loginMyApp');


            if( $_role == EUser::ADMIN )
              return route('admin.login');

            return route('home');

        }

    }
}
