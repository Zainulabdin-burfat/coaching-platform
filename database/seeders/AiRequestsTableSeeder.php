<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AiRequestsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::table('ai_requests')->insert([
            [
                'user_id' => 1,
                'prompt' => 'Generate a fitness workout plan',
                'response' => 'Here is a 5-day workout plan...',
                'model' => 'gpt-4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
