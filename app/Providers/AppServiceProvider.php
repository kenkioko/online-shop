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
      // dashboard component
      Blade::component('components.dash.modal', 'modal');
      Blade::component('components.dash.data_table', 'data_table');
      Blade::component('components.dash.sidebar', 'dash_sidebar');

      // website components
      Blade::component('components.breadcrum', 'breadcrum');
      Blade::component('components.grid', 'grid');
      Blade::component('components.logout_form', 'logout');
      Blade::component('components.modal', 'modal');
      Blade::component('components.nav', 'nav');
      Blade::component('components.search_form', 'search');
      Blade::component('components.show_item_price', 'show_item_price');
      Blade::component('components.side_menu', 'side_menu');
    }
}
