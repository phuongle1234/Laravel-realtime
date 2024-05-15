<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectSeeder extends Seeder
{

    // public $_subject;

    // public function __construct( $_subject = null)
	// {
    //     $this->_subject = $_subject;
    // }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run( $_subject = null )
    {
        \App\Models\Subject::insert($_subject);
    }
}



