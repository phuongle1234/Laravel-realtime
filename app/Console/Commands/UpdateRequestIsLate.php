<?php

namespace App\Console\Commands;

use App\Services\RequestService;
use Illuminate\Console\Command;


class UpdateRequestIsLate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $_service;

    protected $signature = 'request:is_late';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update request is late';

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
            $this->_service->updateRequestIsLate();
        } catch (\Throwable $e) {
            report( "schedule update request is late \n".$e );
        }
    }
}
