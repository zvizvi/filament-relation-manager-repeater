<?php

namespace Zvizvi\FilamentRelationManagerRepeatable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Zvizvi\FilamentRelationManagerRepeatable\FilamentRelationManagerRepeatable
 */
class FilamentRelationManagerRepeatable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Zvizvi\FilamentRelationManagerRepeatable\FilamentRelationManagerRepeatable::class;
    }
}
