<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class UserPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $_user_id = collect( User::where('role','student')->get()->toArray() )->map( function($val,$inx){ return $val['id'];  } )->toArray();

        return [
            'user_id' =>  $this->faker->randomElement( $_user_id ),
            'amount' => $this->faker->randomElement( [5, -1] ),
            'status' => 'active'
        ];
    }
}
