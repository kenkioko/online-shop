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
      // dashboard components
      Blade::component('components.dash.modal', 'modal');
      Blade::component('components.dash.data_table', 'data_table');
      Blade::component('components.dash.sidebar', 'dash_sidebar');
      Blade::component('components.dash.address_form', 'address_form');

      // website components
      Blade::component('components.breadcrum', 'breadcrum');      
      Blade::component('components.carousel', 'carousel');      
      Blade::component('components.grid', 'grid');
      Blade::component('components.logout_form', 'logout');
      Blade::component('components.modal', 'modal');
      Blade::component('components.nav_bar', 'nav_bar');
      Blade::component('components.nav_category', 'nav_category');
      Blade::component('components.nav_profile', 'nav_profile');      
      Blade::component('components.search_form', 'search');
      Blade::component('components.show_item_price', 'show_item_price');
      Blade::component('components.side_menu', 'side_menu');
    }
}
