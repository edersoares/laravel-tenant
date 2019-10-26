<?php

namespace EderSoares\Laravel\Tenant\Contracts;

interface TenantManager
{
    /**
     * Swap tenant connection.
     *
     * @param Tenant $tenant
     *
     * @return void
     */
    public function swap(Tenant $tenant);
}
