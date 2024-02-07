<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Allowance;
class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::factory()->count(5)->create()->each(function ($user) {
            $allowances = Allowance::inRandomOrder()->limit(3)->get();
            
            foreach ($allowances as $allowance) {
                $user->allowances()->attach($allowance, [
                    'amount' => rand(1000, 5000),
                    'year' => rand(2020, 2023),
                ]);
            }
        });
    }
}
