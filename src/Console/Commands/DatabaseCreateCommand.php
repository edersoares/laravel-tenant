<?php

namespace EderSoares\Laravel\Tenant\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;

class DatabaseCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new database';

    /**
     * Execute the console command.
     *
     * @param DatabaseManager $manager
     *
     * @return mixed
     */
    public function handle(DatabaseManager $manager)
    {
        $name = $this->argument('name');
        $manager = $manager->getDoctrineSchemaManager();
        $databases = $manager->listDatabases();

        if (in_array($name, $databases)) {
            $this->error('Database already exists.');

            return;
        }

        $manager->createDatabase($name);

        $this->info('Database created.');
    }
}
