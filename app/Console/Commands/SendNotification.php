<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NotificationDelivery;

use App\Services\SendNotificationService;
use Carbon\Carbon;


class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $_service;

    protected $signature = 'send:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send notices to members from admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SendNotificationService $_service)
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
            $_noti = NotificationDelivery::where("start_at","<",$_now)->where('status','unsent')->get();

            foreach($_noti as $key => $_row){

                //$_row->update([ 'status' => 'working' ]);

                $_row->status = 'working';
                $_row->save();

                $this->_service->handle($_row);
            }

            //return true;
        } catch (\Throwable $e) {
            report( "schedule send notification \n".$e->getMessage() );
        }
    }
}
