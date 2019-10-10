<?php

namespace App\Providers;

use App\Category as Category;
use App\Trending;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

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
        Schema::defaultStringLength(191);
        
        $weight = Trending::pluck('weighting')->toArray();
        $min_weight = min($weight) < 1 ? min($weight)+1 : $weight;
        $max_weight = max($weight);
        $categories = Trending::whereBetween('weighting', [$min_weight, $max_weight])->pluck('category_id')->toArray();
        $categories = Category::whereIn('id', $categories)->pluck('category_name', 'id')->toArray();

        View::share('categories', $categories);
    }
}
