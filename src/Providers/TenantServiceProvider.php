<?php

namespace EderSoares\Laravel\Tenant\Providers;

use EderSoares\Laravel\Tenant\Contracts\Tenant;
use EderSoares\Laravel\Tenant\Contracts\TenantManager;
use EderSoares\Laravel\Tenant\Contracts\TenantRepository;
use EderSoares\Laravel\Tenant\Managers\TenantSwapper;
use EderSoares\Laravel\Tenant\Models\TenantModel;
use EderSoares\Laravel\Tenant\Repositories\TenantEloquentRepository;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class TenantServiceProvider extends LaravelServiceProvider
{
    /**
     * Bindings.
     *
     * @var array
     */
    public $bindings = [
        Tenant::class => TenantModel::class,
        TenantManager::class => TenantSwapper::class,
        TenantRepository::class => TenantEloquentRepository::class,
    ];

    /**
     * Create tenant (system) connection.
     *
     * @return void
     */
    protected function createTenantConnection()
    {
        $config = $this->app['config'];

        $connections = $config->get('database.connections');

        $connections['tenant'] = $this->app['db']->getConfig();

        $config->set([
            'database.connections' => $connections,
        ]);
    }

    /**
     * Prepare queue payload to set tenant identifier.
     *
     * @return void
     */
    protected function prepareTenantQueue()
    {
        $this->app['queue']->createPayloadUsing(function () {
            return [
                'tenant' => $this->app->get(Tenant::class),
            ];
        });

        $this->app['events']->listen(JobProcessing::class, function ($event) {
            $payload = $event->job->payload();

            if (isset($payload['tenant'])) {
                TenantModel::unguarded(function () use ($payload) {
                    $manager = $this->app->get(TenantManager::class);

                    $manager->swap(new TenantModel($payload['tenant']));
                });
            }
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->createTenantConnection();

        $this->prepareTenantQueue();

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
