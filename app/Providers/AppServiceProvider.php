<?php

namespace App\Providers;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

use View;
use App\Currency;
use App\setting;
use App\Symbol;
use App\Transfer;
use Illuminate\Support\Facades\Session;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {

        $setting = Setting::where('name','contract')->first();

        Schema::defaultStringLength(191);
        View::share('currencies', Currency::orderBy('name', 'asc')->get());
        View::share('contract_doc', asset('storage/'.$setting->value));

        View::share('allSymbol', Symbol::orderBy('id', 'asc')->get());



        view()->composer('*', function ($view)
        {
            if(\Session::get('currency')){
                $view->with('allSymbol', Symbol::where('currency', \Session::get('currency'))->first()  );
            }
            else{
                $view->with('allSymbol', Symbol::where('currency', 'INR')->first()  );
            }



        });



    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
            //
    }
}