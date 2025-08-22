<?php

namespace App\Providers;

use App\Models\ProspectAradial;
use App\Policies\ContractorPolicy;
use App\Policies\ProspectPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Contractor;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Contractor::class => ContractorPolicy::class,
        ProspectAradial::class => ProspectPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies(); 
    }
}