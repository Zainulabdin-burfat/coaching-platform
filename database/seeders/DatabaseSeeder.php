<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Jobs\SeedClientsAndSessions;
use App\Models\User;
use App\Models\Coach;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the database with coaches efficiently using queues.
     */
    public function run()
    {
        $faker = Faker::create();
        $batchSize = 10; // Adjust batch size for efficiency

        for ($i = 0; $i < 1000; $i += $batchSize) {
            DB::transaction(function () use ($faker, $batchSize) {
                $coaches = collect();

                for ($j = 0; $j < $batchSize; $j++) {
                    $coaches->push(User::create([
                        'name' => $faker->name,
                        'email' => $faker->unique()->safeEmail,
                        'password' => Hash::make('password'),
                        'role' => 'coach',
                    ]));
                }

                // Insert into `coaches` table
                foreach ($coaches as $user) {
                    Coach::create([
                        'user_id' => $user->id,
                        'specialization' => $faker->word,
                        'bio' => $faker->sentence,
                    ]);
                }

                // Dispatch jobs to create clients & sessions
                foreach ($coaches as $coach) {
                    SeedClientsAndSessions::dispatch($coach->id)->onQueue('seeding')->delay(now()->addSeconds(5));
                }
            });
        }
    }
}
