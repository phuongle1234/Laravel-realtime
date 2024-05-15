<?php

namespace App\Http\Middleware;

use App\Enums\EUser;
use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Enums\EStatus;
use App\Jobs\UserLastActivityjob;
use Illuminate\Foundation\Events\Dispatchable;

class UserLastActivity
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {

        $_role = explode('.',$request->route()->getName())[0];

        if( !in_array($_role,['student', 'teacher']) )
             return $next($request);

        $_user = Auth::guard($_role)->user();

        if( isset($_user) && $_user->status != EStatus::DELETE ){
            dispatch( new UserLastActivityjob($_user) )->afterCommit();
            return $next($request);
        }

        // if($_user->status === EUser::STATUS_ACTIVE)
            return redirect()->route('loginMyApp');

    }
}
