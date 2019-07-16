<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use DB;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //      phan quyen chinh sua user
        Gate::define('admin', function($user){
            return $user->id==1;
        });
        
        //      phan quyen channel
        Gate::define('content-view', function($user,$content){
            return $user->id==$content;
        });

        //      phan quyen active
        Gate::define('status', function($user){
            return 1==$user->status;
        });

        //      so luong kenh the doi/user
        Gate::define('sl', function($user,$sl){
            return $user->sl>=$sl;
        });

        //      so luong kenh sub cheo
        Gate::define('sub-cheo', function($user,$sl){
            return $user->sl_kenhrac<=$sl;
        });

        //      so luong sub con lai
        Gate::define('sub-conlai', function($user,$sl){
            return 0<$sl;
        });
        
    }
}
