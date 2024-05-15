<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VimeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Vimeos::insert([
            ['client_id' => env('VIMEO_CLIENT'),
            'client_secrets' => env('VIMEO_SECRET'),
            'personal_access_token' => env('VIMEO_ACCESS'),
            ],
        ]);
    }
}
