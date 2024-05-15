<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;


class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot(Request $request)
    {
       //$_auth = explode('_',$request->path() )[0];

       Broadcast::routes(['middleware' => ['auth:sanctum'] ]);
       require base_path('routes/channels.php');
    }
}
