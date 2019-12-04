<?php

namespace EderSoares\Laravel\Tenant\Http\Middleware;

use Closure;
use EderSoares\Laravel\Tenant\Contracts\TenantRepository;
use EderSoares\Laravel\Tenant\Contracts\TenantManager;
use Illuminate\Http\Request;

class ConnectTenant
{
    /**
     * @var Closure
     */
    private static $resolver;

    /**
     * @var TenantManager
     */
    protected $manager;

    /**
     * @var TenantRepository
     */
    protected $tenants;

    /**
     * @param TenantManager    $manager
     * @param TenantRepository $tenants
     */
    public function __construct(
        TenantManager $manager,
        TenantRepository $tenants
    ) {
        $this->manager = $manager;
        $this->tenants = $tenants;
    }

    /**
     * @return Closure
     */
    public static function getResolver()
    {
        if (self::$resolver) {
            return self::$resolver;
        }

        return function (Request $request) {
            return $request->getHost();
        };
    }

    /**
     * @param Closure $resolver
     *
     * @return void
     */
    public static function setResolver(Closure $resolver)
    {
        self::$resolver = $resolver;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function getRequestTenant(Request $request)
    {
        $resolver = $this->getResolver();

        return $resolver($request);
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $tenant = $this->tenants->get(
            $this->getRequestTenant($request)
        );

        if ($tenant) {
            $this->manager->swap($tenant);
        }

        return $next($request);
    }
}
