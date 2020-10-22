<?php

namespace Dex\Laravel\Tenant\Console\Commands;

use Dex\Laravel\Tenant\Contracts\TenantRepository;
use Illuminate\Console\Command;

class TenantDropCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:drop {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a tenant';

    /**
     * Execute the console command.
     *
     * @param TenantRepository $repository
     *
     * @return void
     */
    public function handle(TenantRepository $repository) {
        $id = $this->argument('id');

        if ($repository->delete($id)) {
            $this->info('Tenant deleted.');

            return;
        }

        $this->error('Tenant cannot be deleted.');
    }
}
