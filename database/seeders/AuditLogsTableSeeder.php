<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AuditLogsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('audit_logs')->insert([
            [
                'user_id' => 1,
                'event_type' => 'login',
                'event_data' => json_encode(['ip' => '192.168.1.1']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
