<?php

namespace EderSoares\Laravel\Tenant\Console;

use Illuminate\Console\Application;

trait TenantConsoleKernel
{
    /**
     * Get the Artisan application instance.
     *
     * @see Application
     *
     * @return Artisan
     */
    protected function getArtisan()
    {
        if (is_null($this->artisan)) {
            $artisan = new Artisan(
                $this->app,
                $this->events,
                $this->app->version()
            );

            $this->artisan = $artisan->resolveCommands($this->commands);
        }

        return $this->artisan;
    }
}
