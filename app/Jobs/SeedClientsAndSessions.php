<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Client;
use App\Models\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class SeedClientsAndSessions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5; // Retry failed jobs
    public $timeout = 600; // Increase timeout for large inserts
    protected $coachId;

    public function __construct($coachId)
    {
        $this->coachId = $coachId;
    }

    public function handle()
    {
        $faker = \Faker\Factory::create();
        $batchSize = 50;

        DB::transaction(function () use ($faker, $batchSize) {
            // Insert Clients
            $clients = collect();

            for ($i = 0; $i < $batchSize; $i++) {
                $clients->push(User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make('password'),
                    'role' => 'client',
                ]));
            }

            // Insert Clients Data
            $clientsData = collect();
            foreach ($clients as $user) {
                $clientsData->push(Client::create([
                    'user_id' => $user->id,
                    'coach_id' => $this->coachId,
                    'progress' => 0,
                ]));
            }

            // Ensure clients exist before inserting sessions
            if ($clientsData->isEmpty()) {
                return;
            }

            // Insert Sessions
            $sessions = collect();
            foreach ($clientsData as $client) {
                for ($j = 0; $j < 20; $j++) {
                    $sessions->push(Session::create([
                        'coach_id' => $this->coachId,
                        'client_id' => $client->id,
                        'scheduled_at' => now()->addDays(rand(1, 90)),
                        'completed' => false,
                    ]));
                }
            }
        });
    }
}
