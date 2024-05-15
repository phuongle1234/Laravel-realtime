<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\School::insert([
            0 => ['name' => '口座名義人'],
            1 => ['name' => '口座名義人'],
            2 => ['name' => '科目口座名義人'],
            3 => ['name' => '科口座名義人目'],
            4 => ['name' => '科口座名義人目'],
            5 => ['name' => '科口座名義人目'],
            6 => ['name' => '科目口座名義人'],
        ]);
    }
}
