<?php

namespace EderSoares\Laravel\Tenant\Contracts;

use Illuminate\Support\Collection;

interface TenantRepository
{
    /**
     * Create tenant.
     *
     * @param string $name
     * @param string $slug
     * @param string $host
     * @param array  $database
     * @param bool   $active
     *
     * @return Tenant
     */
    public function create($name, $slug, $host, $database, $active = true);

    /**
     * Delete tenant.
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete($id);

    /**
     * Fetch tenant.
     *
     * @param int $id
     *
     * @return Tenant
     */
    public function fetch($id);

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
