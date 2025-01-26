<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('sessions')->insert([
            [
                'coach_id' => 1,
                'client_id' => 1,
                'scheduled_at' => now()->addDays(3),
                'completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
