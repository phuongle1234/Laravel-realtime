<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;
use Carbon\Carbon;

class SeenMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $_message_id;
    private $_user_id;

    public function __construct($_user_id, $_message_id)
    {
        $this->_user_id = $_user_id;
        $this->_message_id = $_message_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{

            Contact::where(['user_id' =>  $this->_user_id ,'message_id' => $this->_message_id])
                     ->update(['seen_at' => Carbon::now()->format('Y-m-d H:i') ]);

        } catch (\Throwable $th) {
            report("job event seen message \n = " . $th->getMessage() . "\n");
        }
    }
}
