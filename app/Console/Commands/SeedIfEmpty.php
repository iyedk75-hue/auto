<?php

namespace App\Console\Commands;

use App\Models\AutoSchool;
use App\Models\Question;
use App\Models\User;
use Illuminate\Console\Command;

class SeedIfEmpty extends Command
{
    protected $signature = 'codex:seed-if-empty {--force : Force seeding in production}';

    protected $description = 'Seed the database only when it is empty.';

    public function handle(): int
    {
        if (User::query()->exists() || AutoSchool::query()->exists() || Question::query()->exists()) {
            $this->info('Database already has records. Skipping seeding.');
            return self::SUCCESS;
        }

        $force = (bool) $this->option('force');

        $this->info('Database is empty. Running seeders...');
        $this->call('db:seed', [
            '--force' => $force,
        ]);

        return self::SUCCESS;
    }
}
