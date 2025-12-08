<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup {--fresh : Run fresh migrations before setup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup fresh database: migrate, seed, import data, populate labels, create default user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting database setup...');

        // Run fresh migrations if requested
        if ($this->option('fresh')) {
            $this->info('Running fresh migrations...');
            $this->call('migrate:fresh');
        }

        // Run seeders
        $this->info('Running seeders...');
        $this->call('db:seed');

        // Import WooCommerce data
        $this->info('Importing product data...');
        $this->call('app:import-data');

        // Populate variation labels
        $this->info('Populating variation labels...');
        $this->call('variations:populate-labels');

        $this->newLine();
        $this->info('Database setup completed successfully!');

        return self::SUCCESS;
    }
}
