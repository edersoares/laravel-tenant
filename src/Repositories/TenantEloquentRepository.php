<?php

namespace EderSoares\Laravel\Tenant\Repositories;

use EderSoares\Laravel\Tenant\Contracts\Tenant;
use EderSoares\Laravel\Tenant\Contracts\TenantRepository;
use EderSoares\Laravel\Tenant\Models\TenantModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TenantEloquentRepository implements TenantRepository
{
    /**
     * @return Builder
     */
    protected function newQuery()
    {
        return TenantModel::query();
    }

    /**
     * @param string $tenant
     *
     * @return bool
     */
    public function exists($tenant)
    {
        return $this->newQuery()
            ->where('slug', $tenant)
            ->orWhere('host', $tenant)
            ->exists();
    }

    /**
     * @param string $tenant
     *
     * @return Tenant
     */
    public function get($tenant)
    {
        return $this->newQuery()
            ->where('slug', $tenant)
            ->orWhere('host', $tenant)
            ->first();
    }

    /**
     * @param array $tenants
     *
     * @return Collection
     */
    public function getTenants(array $tenants = [])
    {
        return $this->newQuery()->when($tenants, function ($query) use ($tenants) {
            $query->whereIn('slug', $tenants);
        })->get();
    }
}
