<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NotificationDelivery;
use App\Services\RequestService;
use App\Enums\EStatus;
use Carbon\Carbon;


class Request extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $_service;

    protected $signature = 'request:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'handle the case for request';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(RequestService $_service)
    {
        $this->_service = $_service;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            $_now = Carbon::now()->format('Y-m-d H:i:s');

            $_request = \App\Models\Request::whereNotIn('status',EStatus::NOT_SCHEDULE_REQUEST)
                                            ->with(['student','teacher'])
                                            ->where('deadline','<',$_now)->get();

            foreach($_request as $_key => $_row){
                switch($_row->status){
                    case EStatus::PENDING: $this->_service->refund($_row); break;
                    case EStatus::DENIED:
                    case EStatus::ACCEPTED: $this->_service->updateRequestIsLate($_row); break;
                    case EStatus::APPROVED: $this->_service->updatePassVideo($_row); break;
                    case EStatus::PASS: $this->_service->handleComplete($_row); break;
                }
           }

        } catch (\Throwable $e) {
            report($e);
        }
    }
}
