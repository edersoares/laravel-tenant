<?php

namespace Dex\Laravel\Tenant\Managers;

use Dex\Laravel\Tenant\Contracts\Tenant;
use Dex\Laravel\Tenant\Contracts\TenantManager;
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
    private function overrideConnections(Tenant $tenant)
    {
        $config = $this->container['config'];

        $config->set([
            'database.connections' => array_merge(
                $config->get('database.connections'),
                $this->getTenantConnection($tenant)
            ),
            'telescope.storage.database.connection' => $tenant->getIdentifier(),
        ]);
    }

    /**
     * @param Tenant $tenant
     *
     * @return array
     */
    private function getTenantConnection(Tenant $tenant)
    {
        return [
            $tenant->getIdentifier() => $tenant->getDatabaseConnection(),
        ];
    }

    /**
     * @param Tenant $tenant
     *
     * @return void
     */
    public function swap(Tenant $tenant)
    {
        $this->overrideConnections($tenant);

        $this->container->bind(Tenant::class, function () use ($tenant) {
            return $tenant;
        });

        $this->manager->setDefaultConnection(
            $tenant->getIdentifier()
        );
    }
}
