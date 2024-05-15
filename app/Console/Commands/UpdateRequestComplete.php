<?php

namespace App\Console\Commands;

use App\Services\RequestService;
use Illuminate\Console\Command;


class UpdateRequestComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $_service;

    protected $signature = 'request:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update request complete';

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
            $this->_service->updateRequestComplete();
        } catch (\Throwable $e) {
            report( "schedule update request complete \n".$e );
        }
    }
}
