<?php

namespace App\Traits;



use Webpatser\Uuid\Uuid;

trait Uuids
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(
        /**
         * @throws \Exception
         */ function ($model){
            if (is_null($model->{$model->getKeyName()})){
                $model->{$model->getKeyName()} = Uuid::generate()->string;
            }
        });
    }
}
