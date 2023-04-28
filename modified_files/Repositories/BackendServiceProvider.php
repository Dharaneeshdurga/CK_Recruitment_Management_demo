<?php
namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{

    public function register() {
        
        $this->app->bind(
            'App\Repositories\ICoordinatorRepository',
            'App\Repositories\CoordinatorRepository'
        );
        $this->app->bind(
            'App\Repositories\IRecruiterRepository',
            'App\Repositories\RecruiterRepository'
        );
        
    }
}
?>