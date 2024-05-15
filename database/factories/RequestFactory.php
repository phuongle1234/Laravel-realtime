<?php

namespace Database\Factories;

use App\Enums\EStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Subject;

class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $_user_id = collect( User::where('role','student')->get()->toArray() )->map( function($val,$inx){ return $val['id'];  } )->toArray();

        $_subject_id = collect( Subject::all()->take(10)->toArray() )->map( function($val,$inx){ return $val['id'];  } )->toArray();
        $_is_direct = $this->faker->randomElement( [0,1] );

        $_user_receive = null;
        if($_is_direct){
            $_user_receive = collect( User::where('role','teacher')->get()->toArray() )->map( function($val,$inx){ return $val['id'];  } )->toArray();
            $_user_receive = $this->faker->randomElement( $_user_receive );
        }

        return [

            'user_id' => $this->faker->randomElement( $_user_id ),
            'subject_id' => $this->faker->randomElement( $_subject_id ),
            'user_receive_id' => $_user_receive,
            'title' => $this->faker->text(10),
            'content' => $this->faker->text(300),
            'private' =>  $this->faker->randomElement( [0,1] ),
            'status' => EStatus::PENDING,
            'is_direct' => $_is_direct

            // 'deadline' => $this->faker->date('Y-m-d', 'now').",".$this->faker->date('Y-m-d', '+1 week')

            // 'name' => $this->faker->name,
            // 'content' => $this->faker->text(300),
            // 'destination' => $this->faker->randomElement( ['all','teacher','student'] ),
            // 'display' => $this->faker->randomElement( [0,1] )
        ];
    }
}
