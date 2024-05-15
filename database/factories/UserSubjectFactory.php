<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Subject;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserSubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */


    public function definition()
    {
        $_user_id = collect( User::where('role','teacher')->get()->toArray() )->map( function($val,$inx){ return $val['id'];  } )->toArray();
        $_subject_id = collect( Subject::all()->take(10)->toArray() )->map( function($val,$inx){ return $val['id'];  } )->toArray();

        return [
            'user_id' => $this->faker->randomElement( $_user_id ),
            'subject_id' => $this->faker->randomElement( $_subject_id )
        ];
    }
}
