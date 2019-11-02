<?php

namespace EderSoares\Laravel\Tenant\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;

class DatabaseListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all databases';

    /**
     * Execute the console command.
     *
     * @param DatabaseManager $manager
     *
     * @return mixed
     */
    public function handle(DatabaseManager $manager)
    {
        $databases = $manager->getDoctrineSchemaManager()->listDatabases();

        $databases = collect($databases)->sort()->map(function ($database) {
            return [$database];
        });

        $this->table(['Database'], $databases);
    }
}
