<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Allowance>
 */
// database/factories/AllowanceFactory.php
use App\Models\Allowance;
class AllowanceFactory extends Factory
{
    protected $model = Allowance::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            // Add other fields as needed
        ];
    }
}
