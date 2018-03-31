<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     * 该属性用于将各种模型对应到管理它们的授权策略上。我们需要为用户模型 User 指定授权策略 UserPolicy。
     *
     * @var array
     */
    protected $policies = [
		 \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
