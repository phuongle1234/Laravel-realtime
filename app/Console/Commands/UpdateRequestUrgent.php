<?php

namespace App\Console\Commands;

use App\Services\RequestService;
use Illuminate\Console\Command;


class UpdateRequestUrgent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $_service;

    protected $signature = 'request:urgent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update request urgent';

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
            $this->_service->updateRequestUrgent();
        } catch (\Throwable $e) {
            report( "schedule update request urgent \n".$e );
        }
    }
}
