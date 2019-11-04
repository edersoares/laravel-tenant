<?php

namespace EderSoares\Laravel\Tenant\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;

class DatabaseDropCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:drop {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop a existing database';

    /**
     * Execute the console command.
     *
     * @param DatabaseManager $manager
     *
     * @return void
     */
    public function handle(DatabaseManager $manager)
    {
        $name = $this->argument('name');
        $manager = $manager->getDoctrineSchemaManager();
        $databases = $manager->listDatabases();

        $databases = collect($databases)->sort()->values()->toArray();

        if (empty($name)) {
            $name = $this->choice('Drop which database?', $databases);
        }

        if (!in_array($name, $databases)) {
            $this->error('Database not exists.');

            return;
        }

        if (!$this->confirm("Confirm drop database {$name}?")) {
            return;
        }

        $manager->dropDatabase($name);

        $this->info('Database dropped.');
    }
}
