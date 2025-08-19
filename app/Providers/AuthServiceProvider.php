<?php

namespace App\Providers;

use App\Models\ProspectAradial;
use App\Policies\ProspectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        
        ProspectAradial::class => ProspectPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies(); // ✅ Esto sí es necesario
    }
}