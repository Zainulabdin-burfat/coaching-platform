<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CoachesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::table('coaches')->insert([
            [
                'user_id' => 2, // Assuming the second user is a coach
                'specialization' => 'Fitness & Health',
                'bio' => $faker->paragraph,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
