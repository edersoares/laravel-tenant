<?php

return [

    'database' => env('TENANT_DATABASE', 'tenant'),

    'host_prefix' => env('TENANT_PREFIX'),
    'host_suffix' => env('TENANT_SUFFIX'),

    'rejected_databases' => [
        //
    ],

];
