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
     * New query builder.
     *
     * @return Builder
     */
    protected function newQuery()
    {
        return TenantModel::query();
    }

    /**
     * Create tenant.
     *
     * @param array $attributes
     *
     * @return Tenant
     */
    public function create($attributes)
    {
        /** @var Tenant $tenant */
        $tenant = $this->newQuery()->create($attributes);

        return $tenant;
    }

    /**
     * Delete tenant.
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete($id)
    {
        return boolval($this->newQuery()->whereKey($id)->delete());
    }

    /**
     * Fetch tenant.
     *
     * @param int $id
     *
     * @return Tenant
     */
    public function fetch($id)
    {
        /** @var Tenant $tenant */
        $tenant = $this->newQuery()->find($id);

        return $tenant;
    }

    /**
     * Indicate if tenant exists.
     *
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
     * Return tenant.
     *
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
     * Return tenants.
     *
     * @param array $tenants
     *
     * @return Collection
     */
    public function getTenants(array $tenants = [])
    {
        return $this->newQuery()->when($tenants, function ($query) use ($tenants) {
            $query->whereIn('slug', $tenants)->orWhereIn('host', $tenants);
        })->orderBy('id')->get();
    }
}
