<?php

namespace EderSoares\Laravel\Tenant\Contracts;

use Illuminate\Support\Collection;

interface TenantRepository
{
    /**
     * Indicate if tenant exists.
     *
     * @param string $tenant
     *
     * @return bool
     */
    public function exists($tenant);

    /**
     * Return tenant.
     *
     * @param string $tenant
     *
     * @return Tenant
     */
    public function get($tenant);

    /**
     * Return all tenants.
     *
     * @param array $tenants
     *
     * @return Collection
     */
    public function getTenants(array $tenants = []);
}
