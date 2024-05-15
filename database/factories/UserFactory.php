<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use App\Enums\EUser;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $flag_stt = 0;

    public function definition()
    {
        if(! User::where('email', '=', 'admin@admin.com')->exists() && ($this->flag_stt == 0) ){
            $_email = 'admin@admin.com';
            $_role = 'admin';
        }else{
            $_email = $this->faker->unique()->safeEmail();
            //,'student'
            $_role = $this->faker->randomElement( ['admin','teacher'] );
        }

        $_fake = [
                    'name' => $this->faker->name(),
                    'code' => EUser::getCodeUser($_role),
                    'email' => $_email,
                    'role' => $_role,
                    'last_name' => 'お名',
                    'first_name' => '前太郎',
                    // 'avatar',
                    'kana' => "電話で私",
                    //$this->faker->regexify('/^[\u3040-\u30ff]{10}/'),
                    //$this->faker->regexify('/^[ぁ-んァ-ン一-龥]{10}/')
                    'birthday' => $this->faker->date('Y-m-d','now'),
                    'sex' => $this->faker->randomElement( ['male','female'] ), //,'other'
                    'tel' => $this->faker->e164PhoneNumber(),
                    'address' => $this->faker->address(),
                    // 'email_verified_at' => now(),
                    'password' => Hash::make('abc123456'),
                    'remember_token' => Str::random(10),
                    'status' => 'active' //$this->faker->randomElement( ['active','pending'] )
                ];
        if( $_role == 'admin' )
            $_fake['password_crypt'] = Crypt::encryptString('abc123456');

        $this->flag_stt ++;

        return $_fake;
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

     /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (User $user) {
            //
        })->afterCreating(function (User $user) {
            //
        });
    }
}
