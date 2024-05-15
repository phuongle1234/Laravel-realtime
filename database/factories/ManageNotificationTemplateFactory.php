<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ManageNotificationTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'title' => $this->faker->word,
            'content' => $this->faker->text(300),
            'destination' => $this->faker->randomElement( ['all','teacher','student'] ),
            'display' => $this->faker->randomElement( [0,1] )
        ];
    }
}
