<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Allowance;

class AllowancesTableSeeder extends Seeder
{
    public function run()
    {
        Allowance::factory()->count(5)->create();
    }
}