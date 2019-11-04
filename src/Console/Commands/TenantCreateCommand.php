<?php

namespace EderSoares\Laravel\Tenant\Console\Commands;

use EderSoares\Laravel\Tenant\Contracts\TenantRepository;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Str;
use Throwable;

class TenantCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a tenant';

    /**
     * @return array
     */
    protected function getRejectedDatabases()
    {
        return config('tenant.rejected_databases');
    }

    /**
     * @param string $slug
     *
     * @return string
     */
    protected function getPossibleHost($slug)
    {
        return config('tenant.host_prefix') . $slug . config('tenant.host_suffix');
    }

    /**
     * Execute the console command.
     *
     * @param DatabaseManager  $manager
     * @param TenantRepository $repository
     *
     * @return void
     */
    public function handle(
        DatabaseManager $manager,
        TenantRepository $repository
    ) {
        $schema = $manager->getDoctrineSchemaManager();
        $databases = $schema->listDatabases();
        $rejects = $this->getRejectedDatabases();

        $databases = collect($databases)->reject(function ($database) use ($rejects) {
            return in_array($database, $rejects);
        })->sort()->values()->toArray();

        $name = $this->ask('What\'s name?');
        $slug = $this->ask('What\'s slug?', Str::slug($name));
        $host = $this->ask('What\'s host?', $this->getPossibleHost($slug));
        $choose = $this->choice('Which database?', [
            'new database', 'choose a existing database'
        ], 'new database');

        if ($choose === 'new database') {
            $database = $this->ask('What\'s database name?', Str::slug($slug, '_'));

            $this->call('database:create', ['name' => $database]);
        } else {
            $database = $this->choice('Which database?', $databases, '');
        }

        $config = $manager->getConfig();
        $config['database'] = $database;

        if ($repository->exists($slug)) {
            $this->error("Already exists a tenant with slug {$slug}.");

            return;
        }

        if ($repository->exists($host)) {
            $this->error("Already exists a tenant with host {$host}.");

            return;
        }

        try {
            $repository->create($name, $slug, $host, $config);
        } catch (Throwable $throwable) {
            $this->error('Tenant cannot be created.');

            return;
        }

        $this->info('Tenant created.');
    }
}
