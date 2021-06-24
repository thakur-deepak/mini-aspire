<?php

namespace App\Providers;

use App\Formatter\JsonOutput;
use App\Formatter\OutputInterface;
use App\Repositories\Loan\LoanInterface;
use App\Repositories\Loan\LoanRepository;
use App\Repositories\Repayment\RepaymentInterface;
use App\Repositories\Repayment\RepaymentRepository;
use App\Repositories\User\UserInterface;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RepaymentInterface::class,RepaymentRepository::class);
        $this->app->singleton(LoanInterface::class,LoanRepository::class);
        $this->app->singleton(UserInterface::class,UserRepository::class);
        $this->app->singleton(OutputInterface::class,JsonOutput::class);
    }

    public function boot(): void
    {
         //
    }
}
