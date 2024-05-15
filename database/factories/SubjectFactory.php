<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'icon' => $this->faker->randomElement([
                            'common_img/icon/icon1.svg',
                            'common_img/icon/icon2.svg',
                            'common_img/icon/icon3.svg'
                        ]),
            'name' => $this->faker->randomElement($_array)
        ];

    }
}


