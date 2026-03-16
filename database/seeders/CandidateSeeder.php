<?php

namespace Database\Seeders;

use App\Models\AutoSchool;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        $target = 300;
        $existing = User::query()->where('role', User::ROLE_CANDIDATE)->count();
        $toCreate = max(0, $target - $existing);

        if ($toCreate === 0) {
            return;
        }

        $schools = AutoSchool::query()->pluck('id');
        if ($schools->isEmpty()) {
            $school = AutoSchool::create([
                'name' => 'Auto-École Massar Centre',
                'city' => 'Tunis',
                'address' => 'Avenue Habib Bourguiba',
                'whatsapp_phone' => '+216 99 000 000',
                'is_active' => true,
            ]);
            $schools = collect([$school->id]);
        }

        $faker = FakerFactory::create('fr_FR');
        $existingEmails = User::query()->pluck('email')->mapWithKeys(fn ($email) => [$email => true])->all();
        $passwordHash = Hash::make('password');
        $now = now();
        $batchSize = 50;
        $rows = [];

        for ($i = 0; $i < $toCreate; $i++) {
            do {
                $email = $faker->unique()->safeEmail();
            } while (isset($existingEmails[$email]));

            $existingEmails[$email] = true;

            $rows[] = [
                'name' => $faker->name(),
                'email' => $email,
                'email_verified_at' => $now,
                'role' => User::ROLE_CANDIDATE,
                'auto_school_id' => $schools->random(),
                'phone' => '+216 '.$faker->numberBetween(20, 99).' '.$faker->numberBetween(100, 999).' '.$faker->numberBetween(100, 999),
                'status' => $faker->randomElement(['active', 'active', 'active', 'inactive']),
                'balance_due' => $faker->randomElement([0, 0, 0, 50, 80, 120, 150, 200]),
                'registered_at' => $faker->dateTimeBetween('-18 months', 'now'),
                'password' => $passwordHash,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($rows) >= $batchSize) {
                User::query()->insert($rows);
                $rows = [];
            }
        }

        if ($rows !== []) {
            User::query()->insert($rows);
        }
    }
}
