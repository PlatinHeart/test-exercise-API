<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->company,
            'phone' => $this->faker->phoneNumber,
            'description' => $this->faker->text(200),
            'user_id'=>function(){
                return User::all()->random()->id;
            },
        ];
    }
}
