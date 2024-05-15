<?php

namespace App\Jobs;

use App\Enums\EStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;
use Carbon\Carbon;

// use App\Services\VideoService;

use Illuminate\Support\Facades\Storage;


class EventLikeJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $_vimeo_id;
    private $_status;

    private $_vimeo_service;

    // private $request;

    public function __construct($_vimeo_id, $_status, $_vimeo_service)
    {
        $this->_vimeo_id = $_vimeo_id;
        $this->_status = $_status;

        $this->_vimeo_service = $_vimeo_service;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        try{

            $this->_vimeo_service->like( $this->_vimeo_id, $this->_status == EStatus::ACTIVE ? false : true );

        } catch (\Throwable $th) {

            report("job upload vimeo api like \n " . $th . "\n");

        }
    }
}
