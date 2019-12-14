<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Blade::component('components.grid', 'grid');
      Blade::component('components.nav', 'nav');
      Blade::component('components.search_form', 'search');
      Blade::component('components.logout_form', 'logout');
      Blade::component('components.breadcrum', 'breadcrum');
      Blade::component('components.dash.sidebar', 'dash_sidebar');
    }
}
