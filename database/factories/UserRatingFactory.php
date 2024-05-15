<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class UserRatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $_user_id = collect( User::where('role','teacher')->get()->toArray() )->map( function($val,$inx){ return $val['id'];  } )->toArray();
        $_assessor_user_id = collect( User::where('role','student')->get()->toArray() )->map( function($val,$inx){ return $val['id'];  } )->toArray();

        return [
            'user_id' => $this->faker->randomElement( $_user_id ),
            'reviewer_id' => $this->faker->randomElement($_assessor_user_id),
            'evaluation' => rand(1,5)
        ];
    }
}
