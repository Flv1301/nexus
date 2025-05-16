<?php

namespace App\Providers;

use App\Repositories\Eloquent\Contracts\PersonRepositoryInterface;
use App\Repositories\Eloquent\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\PersonRepositoryEloquenteORM;
use App\Repositories\Eloquent\UserRepositoryEloquenteORM;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (App::environment('production')) {
            $this->app['request']->server->set('HTTPS', 'on');
            URL::forceScheme('https');
        }

        if (App::environment('local')) {
            DB::connection()->enableQueryLog();
        }

        $this->app->bind(UserRepositoryInterface::class, UserRepositoryEloquenteORM::class);
        $this->app->bind(PersonRepositoryInterface::class, PersonRepositoryEloquenteORM::class);
    }
}
