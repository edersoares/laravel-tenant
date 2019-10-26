<?php

namespace EderSoares\Laravel\Tenant\Models;

use EderSoares\Laravel\Tenant\Contracts\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TenantModel extends Model implements Tenant
{
    /**
     * @var string
     */
    protected $table = 'tenants';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (Model $model) {
            $model->slug = Str::slug($model->name);
        });
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getDatabaseConnection()
    {
        return json_decode($this->database, true);
    }
}
