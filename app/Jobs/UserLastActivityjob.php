<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class UserLastActivityjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $_user = [];

    public function __construct($_user)
    {
        $this->_user = $_user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $_user = $this->_user;
        $_user->seen_at = Carbon::now()->format('Y-m-d H:i');
        $_user->save();
    }
}
