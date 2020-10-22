<?php

namespace Dex\Laravel\Tenant\Contracts;

interface Tenant
{
    /**
     * Return tenant identifier.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Return tenant name.
     *
     * @return string
     */
    public function getName();

    /**
     * Return data to database connection for tenant.
     *
     * @return array
     */
    public function getDatabaseConnection();
}
