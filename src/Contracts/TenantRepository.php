<?php

namespace Dex\Laravel\Tenant\Contracts;

use Illuminate\Support\Collection;

interface TenantRepository
{
    /**
     * Create tenant.
     *
     * @param array $attributes
     *
     * @return Tenant
     */
    public function create($attributes);

    /**
     * Delete tenant.
     *
     * @param int $identifier
     *
     * @return bool
     */
    public function delete($identifier);

    /**
     * Fetch tenant.
     *
     * @param int $identifier
     *
     * @return Tenant
     */
    public function fetch($identifier);

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
