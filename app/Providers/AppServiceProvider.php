<?php

namespace App\Providers;

use App\Models\Option;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        if (!App::environment('local')) {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        if (Schema::hasTable('options')) {
            $options = Option::pluck('option_value', 'option_name')->toArray();
            $lang = (isset($options['language']))? $options['language'] : 'id';
            $tz = (isset($options['timezone']))? $options['timezone'] : 'Asia/Jakarta';
            config(['app.locale' => $lang]);
            Carbon::setLocale($lang);
            date_default_timezone_set($tz);
            // global params config
            config([
                'global' => $options
            ]);
        } else {
            config(['app.locale' => 'id']);
            Carbon::setLocale('id');
            date_default_timezone_set('Asia/Jakarta');
        }

        $this->loadHelpers();
    }

    /**
     * Load all helpers
     *
     * @return boolean
     */
    protected function loadHelpers()
    {
        if (($helpers = glob(__DIR__.'/../Helpers/*.php')) !== false) {
            foreach ($helpers as $filename) {
                require_once $filename;
            }
        }
        return true;
    }
}
