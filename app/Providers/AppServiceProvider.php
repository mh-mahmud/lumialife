<?php

namespace App\Providers;

use App\Models\Settings;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Product;
use App\Support\MediaStorage;
use App\Support\StoreCurrency;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::composer('front.feb.*', function ($view) {
            $febSettings = Settings::first();
            $febCurrency = StoreCurrency::forRequest(request(), $febSettings);
            $febLogoUrl = $febSettings && $febSettings->site_logo
                ? MediaStorage::url($febSettings->site_logo, 'settings', '')
                : asset('feb/img/fabrilife.svg');
            $febFaviconUrl = $febSettings && $febSettings->favicon
                ? MediaStorage::url($febSettings->favicon, 'settings', '')
                : asset('feb/img/favicon.ico');

            $febMenuCategories = Category::where('status', 1)
                ->where('is_menu', 1)
                ->where(function ($query) {
                    $query->whereNull('parent_id')->orWhere('parent_id', 0);
                })
                ->with(['children' => function ($query) {
                    $query->where('status', 1)
                        ->where('is_menu', 1)
                        ->orderBy('category_name');
                }])
                ->orderBy('category_name')
                ->get();

            $menuProductCategoryIds = $febMenuCategories
                ->flatMap(fn ($category) => collect([$category->id])->merge($category->children->pluck('id')))
                ->unique()
                ->values();

            $menuProductsByCategory = Product::where('status', 1)
                ->whereIn('category_id', $menuProductCategoryIds)
                ->latest()
                ->get(['id', 'category_id', 'name', 'slug', 'img_path', 'created_at'])
                ->groupBy('category_id');

            $febMenuCategories->each(function ($category) use ($menuProductsByCategory) {
                $categoryIds = collect([$category->id])->merge($category->children->pluck('id'));
                $menuProducts = $categoryIds
                    ->flatMap(fn ($categoryId) => $menuProductsByCategory->get($categoryId, collect()))
                    ->sortByDesc('created_at')
                    ->take(6)
                    ->values();

                $category->setRelation('menuProducts', $menuProducts);
            });

            $febCartCount = 0;
            if (Auth::check()) {
                $febCartCount = (int) Cart::where('user_id', Auth::id())->sum('quantity');
            } elseif (Session::get('car-clinic-visitor')) {
                $febCartCount = (int) Cart::where('session_id', Session::get('car-clinic-visitor'))->sum('quantity');
            }

            $view->with(compact('febSettings', 'febCurrency', 'febLogoUrl', 'febFaviconUrl', 'febMenuCategories', 'febCartCount'));
        });
    }
}
