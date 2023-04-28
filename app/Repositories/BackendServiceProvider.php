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
        $this->app->bind(
            'App\Repositories\ICardRepository',
            'App\Repositories\CardRepository'
        );
        $this->app->bind(
            'App\Repositories\ICandidateRepository',
            'App\Repositories\CandidateRepository'
        );
        $this->app->bind(
            'App\Repositories\IPayrollRepository',
            'App\Repositories\PayrollRepository'
        );
        $this->app->bind(
            'App\Repositories\ILeaderRepository',
            'App\Repositories\LeaderRepository'
        );
        $this->app->bind(
            'App\Repositories\IExternalRepository',
            'App\Repositories\ExternalRepository'
        );
        $this->app->bind(
            'App\Repositories\IFinanceRepository',
            'App\Repositories\FinanceRepository'
        );
    }
}
?>