<?php

namespace EderSoares\Laravel\Tenant\Console\Commands;

use EderSoares\Laravel\Tenant\Contracts\TenantRepository;
use EderSoares\Laravel\Tenant\Models\TenantModel;
use Illuminate\Console\Command;

class TenantListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all tenants';

    /**
     * Execute the console command.
     *
     * @param TenantRepository $repository
     *
     * @return void
     */
    public function handle(
        TenantRepository $repository
    ) {
        $data = $repository->getTenants()->map(function (TenantModel $tenant) {
            $data = $tenant->only([
                'id', 'name', 'slug', 'host', 'active'
            ]);

            $data['active'] = $data['active'] ? 'true' : 'false';

            return $data;
        });

        $this->table(['ID', 'Name', 'Slug', 'Host', 'Active'], $data);
    }
}
