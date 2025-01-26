<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ClientsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('clients')->insert([
            [
                'user_id' => 3, // Assuming the third user is a client
                'coach_id' => 1, // Assigning to first coach
                'progress' => 25.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
