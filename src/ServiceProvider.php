<?php

namespace DraperStudio\Friendable;

use DraperStudio\ServiceProvider\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    protected $packageName = 'friendable';

    public function boot()
    {
        $this->setup(__DIR__)
             ->publishMigrations();
    }
}
