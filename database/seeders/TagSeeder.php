<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($_tab)
    {
        \App\Models\Tag::insert([
            ['name' => '基礎' , 'tag_type' => 'difficult'],
            ['name' => '応用' , 'tag_type' => 'difficult'],
            ['name' => '難問' , 'tag_type' => 'difficult' ],
        ]);
        \App\Models\Tag::insert($_tab);
    }
}
