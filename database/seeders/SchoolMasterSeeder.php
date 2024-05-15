<?php

namespace Database\Seeders;
use App\Imports\SchoolMasterImpor;
use Excel;
use Illuminate\Database\Seeder;

class SchoolMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $_file_local = __DIR__.'\..\..\public\file_template\SchoolMaster.xlsx';
        Excel::import(new SchoolMasterImpor, $_file_local);
    }
}
