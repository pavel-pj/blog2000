<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class MigrateTestDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:migrate {--fresh : Drop all tables and re-run migrations}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations on test database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Переключаемся на тестовую конфигурацию
        config(['database.default' => 'testing']);

        if ($this->option('fresh')) {
            $this->call('migrate:fresh', [
                '--database' => 'testing',
                '--force' => true,
            ]);
        } else {
            $this->call('migrate', [
                '--database' => 'testing',
                '--force' => true,
            ]);
        }

        $this->info('Test database migrated successfully!');
    }
}
