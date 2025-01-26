<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AiTrainingDataTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::table('ai_training_data')->insert([
            [
                'input_data' => 'What is the best way to lose weight?',
                'expected_output' => 'A combination of calorie deficit and exercise...',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
