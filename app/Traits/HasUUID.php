<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUUID
{
    protected static function bootHasUUID()
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Overriding default incrementing settings
     *
     * @return void
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Overriding default key type
     *
     * @return void
     */
    public function getKeyType()
    {
        return 'string';
    }
}