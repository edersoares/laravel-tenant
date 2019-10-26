<?php

namespace EderSoares\Laravel\Tenant\Managers;

use EderSoares\Laravel\Tenant\Contracts\Tenant;
use EderSoares\Laravel\Tenant\Contracts\TenantManager;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\DatabaseManager;

class TenantSwapper implements TenantManager
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var DatabaseManager
     */
    protected $manager;

    /**
     * @param Container       $container
     * @param DatabaseManager $manager
     */
    public function __construct(Container $container, DatabaseManager $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
    }

    /**
     * @param Tenant $tenant
     *
     * @return void
     */
    public function swap(Tenant $tenant)
    {
        $config = $this->container['config'];

        $connections = $config->get('database.connections');

        $connections[$tenant->getIdentifier()] = $tenant->getDatabaseConnection();

        $this->container['config']->set([
            'database.connections' => $connections,
        ]);

        $this->container->bind(Tenant::class, function () use ($tenant) {
            return $tenant;
        });

        $this->manager->setDefaultConnection($tenant->getIdentifier());
    }
}
