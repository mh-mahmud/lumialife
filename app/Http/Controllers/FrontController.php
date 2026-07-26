<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Cart;
use App\Models\BillingAddress;
use App\Models\Blog;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Career;
use App\Models\Wishlist;
use App\Models\Settings;
use App\Models\ShippingMethod;
use App\Models\OutletLocation;
use App\Models\OutletPageSetting;
use App\Models\HomePageSetting;
use Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;

class FrontController extends Controller
{

    public function home()
    {
        $sliders = Slider::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get(['slider_title', 'slider_image']);

        $newProducts = Product::where('status', 1)
            ->whereDoesntHave('category', fn ($query) => $query->where('category_slug', 'gift-card'))
            ->with([
                'productColors' => fn ($query) => $query->where('product_colors.status', 1)->orderBy('product_colors.name'),
                'productSizes' => fn ($query) => $query->where('product_sizes.status', 1)
                    ->orderBy('product_sizes.sort_order')->orderBy('product_sizes.name'),
            ])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'slug', 'product_value', 'discount_price', 'img_path', 'stock_status', 'stock_quantity']);

        $trendingProducts = Product::where('status', 1)
            ->where('is_trending', 1)
            ->whereDoesntHave('category', fn ($query) => $query->where('category_slug', 'gift-card'))
            ->with([
                'productColors' => fn ($query) => $query->where('product_colors.status', 1)->orderBy('product_colors.name'),
                'productSizes' => fn ($query) => $query->where('product_sizes.status', 1)
                    ->orderBy('product_sizes.sort_order')->orderBy('product_sizes.name'),
            ])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'slug', 'product_value', 'discount_price', 'img_path', 'stock_status', 'stock_quantity']);

        $lifestyleProducts = Product::where('status', 1)
            ->where('is_lifestyle', 1)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'slug', 'product_value', 'discount_price', 'img_path', 'stock_status', 'stock_quantity']);

        $bestDealProducts = Product::where('status', 1)
            ->where('is_best_deal', 1)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'slug', 'product_value', 'discount_price', 'img_path', 'stock_status', 'stock_quantity']);

        $featuredCategories = Category::where('status', 1)
            ->where('is_feature', 1)
            ->orderBy('category_name')
            ->get(['id', 'category_name', 'category_slug', 'category_image']);

        $homePromoCategory = Category::where('status', 1)
            ->where('is_home_promo', 1)
            ->whereNotNull('category_image')
            ->first(['id', 'category_name', 'category_slug', 'category_image']);

        $giftCardCategory = Category::where('status', 1)
            ->where('category_slug', 'gift-card')
            ->first(['id', 'category_name', 'category_slug']);

        $giftCardProducts = $giftCardCategory
            ? Product::where('status', 1)
                ->where('category_id', $giftCardCategory->id)
                ->latest()
                ->limit(4)
                ->get(['id', 'name', 'slug', 'product_value', 'discount_price', 'img_path', 'stock_status', 'stock_quantity'])
            : collect();

        $sliderBottomCategories = Category::where('status', 1)
            ->where('is_slider_bottom', 1)
            ->orderBy('category_name')
            ->get(['id', 'category_name', 'category_slug', 'category_image']);

        $displayCategories = Category::where('status', 1)
            ->where('is_display_products', 1)
            ->whereHas('products', fn ($query) => $query->where('status', 1))
            ->orderBy('category_name')
            ->get();

        $displayCategories->each(function ($category) {
            $category->setRelation('products', $category->products()
                ->where('status', 1)
                ->latest()
                ->limit(8)
                ->get(['id', 'category_id', 'name', 'slug', 'product_value', 'discount_price', 'img_path', 'stock_status', 'stock_quantity']));
        });

        $homePageSetting = HomePageSetting::first();

        return view('front.feb.index', compact(
            'sliders',
            'newProducts',
            'trendingProducts',
            'lifestyleProducts',
            'bestDealProducts',
            'featuredCategories',
            'homePromoCategory',
            'giftCardCategory',
            'giftCardProducts',
            'sliderBottomCategories',
            'displayCategories',
            'homePageSetting'
        ));
    }

    public function single_product($slug = null)
    {
        abort_if(!$slug, 404);

        $productRelations = [
            'category',
            'brand',
            'productColors' => fn ($query) => $query->where('product_colors.status', 1)->orderBy('product_colors.name'),
            'productSizes' => fn ($query) => $query->where('product_sizes.status', 1)
                ->orderBy('product_sizes.sort_order')
                ->orderBy('product_sizes.name'),
        ];

        $product = is_numeric($slug)
            ? Product::with($productRelations)->findOrFail($slug)
            : Product::with($productRelations)->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::where('status', 1)
            ->with([
                'productColors' => fn ($query) => $query->where('product_colors.status', 1)->orderBy('product_colors.name'),
                'productSizes' => fn ($query) => $query->where('product_sizes.status', 1)
                    ->orderBy('product_sizes.sort_order')
                    ->orderBy('product_sizes.name'),
            ])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->latest()
            ->limit(12)
            ->get();

        if ($relatedProducts->count() < 12) {
            $excludedIds = $relatedProducts->pluck('id')->push($product->id);
            $fallbackProducts = Product::where('status', 1)
                ->with([
                    'productColors' => fn ($query) => $query->where('product_colors.status', 1)->orderBy('product_colors.name'),
                    'productSizes' => fn ($query) => $query->where('product_sizes.status', 1)
                        ->orderBy('product_sizes.sort_order')
                        ->orderBy('product_sizes.name'),
                ])
                ->whereNotIn('id', $excludedIds)
                ->latest()
                ->limit(12 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->concat($fallbackProducts);
        }

        return view('front.feb.single-product', compact('product', 'relatedProducts'));
    }

    public function product_list(Request $request)
    {
        $categorySlug = $request->query('category');
        $query = Product::where('status', 1)->with([
            'productColors' => fn ($colorQuery) => $colorQuery
                ->where('product_colors.status', 1)
                ->orderBy('product_colors.name'),
            'productSizes' => fn ($sizeQuery) => $sizeQuery
                ->where('product_sizes.status', 1)
                ->orderBy('product_sizes.sort_order')
                ->orderBy('product_sizes.name'),
        ]);

        $collectionFilters = [
            'new-collection' => 'New Collection',
            'trending' => 'Trending Products',
            'lifestyle' => 'Lifestyle Products',
            'best-deal' => 'Best Deal Products',
        ];
        $selectedCollection = $request->query('collection');
        $selectedCollection = array_key_exists($selectedCollection, $collectionFilters)
            ? $selectedCollection
            : null;
        $selectedCollectionTitle = $selectedCollection
            ? $collectionFilters[$selectedCollection]
            : null;

        if ($selectedCollection === 'new-collection') {
            $query->where('created_at', '>=', now()->subDays(30));
        } elseif ($selectedCollection === 'trending') {
            $query->where('is_trending', 1);
        } elseif ($selectedCollection === 'lifestyle') {
            $query->where('is_lifestyle', 1);
        } elseif ($selectedCollection === 'best-deal') {
            $query->where('is_best_deal', 1);
        }

        $allCategories = Category::where('status', 1)
            ->withCount(['products' => fn ($productQuery) => $productQuery->where('status', 1)])
            ->orderBy('category_name')
            ->get();

        $childrenByParent = $allCategories->groupBy(fn ($category) => (int) $category->parent_id);
        $buildCategoryTree = function ($category) use (&$buildCategoryTree, $childrenByParent) {
            $children = ($childrenByParent->get((int) $category->id) ?? collect())
                ->map(fn ($child) => $buildCategoryTree($child));

            $category->setRelation('children', $children);
            $category->total_products_count = $category->products_count
                + $children->sum('total_products_count');

            return $category;
        };

        $categories = $allCategories
            ->filter(fn ($category) => empty($category->parent_id) || (int) $category->parent_id === 0)
            ->map(fn ($category) => $buildCategoryTree($category))
            ->values();

        $selectedCategory = null;
        if ($categorySlug) {
            $selectedCategory = $allCategories->first(function ($category) use ($categorySlug) {
                return $category->category_slug === $categorySlug
                    || (ctype_digit((string) $categorySlug) && (int) $category->id === (int) $categorySlug);
            });

            if ($selectedCategory) {
                $selectedCategory = $buildCategoryTree($selectedCategory);
                $collectCategoryIds = function ($category) use (&$collectCategoryIds) {
                    return $category->children
                        ->flatMap(fn ($child) => $collectCategoryIds($child))
                        ->push($category->id);
                };

                $categoryIds = $collectCategoryIds($selectedCategory)->unique();
                $query->whereIn('category_id', $categoryIds);
            }
        }

        $selectedSort = $request->query('sort', 'newest');
        if (! in_array($selectedSort, ['newest', 'price-low', 'price-high'], true)) {
            $selectedSort = 'newest';
        }

        if ($selectedSort === 'price-low') {
            $query->orderByRaw('CASE WHEN discount_price > 0 AND discount_price < product_value THEN discount_price ELSE product_value END ASC');
        } elseif ($selectedSort === 'price-high') {
            $query->orderByRaw('CASE WHEN discount_price > 0 AND discount_price < product_value THEN discount_price ELSE product_value END DESC');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        $activeCategoryPath = collect();
        $currentCategory = $selectedCategory;
        while ($currentCategory) {
            $activeCategoryPath->push($currentCategory->id);
            $currentCategory = $allCategories->firstWhere('id', $currentCategory->parent_id);
        }

        return view('front.feb.product-list', compact(
            'products',
            'selectedCategory',
            'categories',
            'activeCategoryPath',
            'selectedCollection',
            'selectedCollectionTitle',
            'selectedSort'
        ));
    }

    public function theme_carts()
    {
        $sessionId = Session::get('car-clinic-visitor');
        $cartQuery = Cart::with('product');

        if (Auth::check()) {
            $cartQuery->where('user_id', Auth::id());
        } elseif ($sessionId) {
            $cartQuery->where('session_id', $sessionId);
        } else {
            $cartQuery->whereRaw('1 = 0');
        }

        $carts = $cartQuery->latest()->get();
        $cartQuantity = (int) $carts->sum('quantity');
        $cartSubtotal = (float) $carts->sum('total_price');
        $cartProductIds = $carts->pluck('product_id')->filter()->unique();
        $categoryIds = $carts->pluck('product.category_id')->filter()->unique();

        $relatedQuery = Product::where('status', 1)->whereNotIn('id', $cartProductIds);
        if ($categoryIds->isNotEmpty()) {
            $relatedQuery->whereIn('category_id', $categoryIds);
        }

        $relatedProducts = $relatedQuery->latest()->limit(8)->get();
        if ($relatedProducts->isEmpty() && $categoryIds->isNotEmpty()) {
            $relatedProducts = Product::where('status', 1)
                ->whereNotIn('id', $cartProductIds)
                ->latest()
                ->limit(8)
                ->get();
        }

        return view('front.feb.theme-carts', compact(
            'carts',
            'cartQuantity',
            'cartSubtotal',
            'relatedProducts'
        ));
    }

    public function side_cart_data()
    {
        $sessionId = Session::get('car-clinic-visitor');
        $cartQuery = Cart::with('product');

        if (Auth::check()) {
            $cartQuery->where('user_id', Auth::id());
        } elseif ($sessionId) {
            $cartQuery->where('session_id', $sessionId);
        } else {
            $cartQuery->whereRaw('1 = 0');
        }

        $carts = $cartQuery->latest()->get();

        return response()->json([
            'cart_count' => (int) $carts->sum('quantity'),
            'cart_subtotal' => (float) $carts->sum('total_price'),
            'cart_url' => route('theme-carts'),
            'checkout_url' => route('theme-checkout'),
            'items' => $carts->map(function ($cart) {
                return [
                    'id' => $cart->id,
                    'name' => $cart->product_name,
                    'image' => $cart->product_image
                        ? \App\Support\MediaStorage::url($cart->product_image, 'products')
                        : asset('uploads/blank.png'),
                    'url' => $cart->product
                        ? route('single-product', $cart->product->slug ?: $cart->product->id)
                        : '#',
                    'color' => $cart->product_color,
                    'size' => $cart->product_size,
                    'quantity' => (int) $cart->quantity,
                    'unit_price' => (float) $cart->unit_price,
                    'line_total' => (float) $cart->total_price,
                ];
            })->values(),
        ]);
    }

    public function theme_checkout()
    {
        $sessionId = Session::get('car-clinic-visitor');
        $cartQuery = Cart::with('product');

        if (Auth::check()) {
            $cartQuery->where('user_id', Auth::id());
        } elseif ($sessionId) {
            $cartQuery->where('session_id', $sessionId);
        } else {
            $cartQuery->whereRaw('1 = 0');
        }

        $carts = $cartQuery->latest()->get();
        if ($carts->isEmpty()) {
            return redirect()->route('theme-carts')->with('error', 'Your cart is empty.');
        }

        $shippingMethods = ShippingMethod::where('status', 1)
            ->orderBy('price')
            ->orderBy('name')
            ->get();

        if ($shippingMethods->isEmpty()) {
            return redirect()->route('theme-carts')->with('error', 'No active shipping method is available.');
        }

        $selectedShippingMethod = $shippingMethods->first();
        $shippingCharge = (float) $selectedShippingMethod->price;
        $cartQuantity = (int) $carts->sum('quantity');
        $cartSubtotal = (float) $carts->sum('total_price');
        $cartTotal = $cartSubtotal + $shippingCharge;

        return view('front.feb.theme-checkout', compact(
            'carts',
            'shippingMethods',
            'selectedShippingMethod',
            'shippingCharge',
            'cartQuantity',
            'cartSubtotal',
            'cartTotal'
        ));
    }

    public function theme_login(Request $request)
    {
        $this->captureCustomerReturn($request);

        if (Auth::check()) {
            return redirect()->to(Session::pull('customer_redirect_url', route('shop-new')));
        }

        return view('front.feb.theme-login');
    }

    public function theme_register(Request $request)
    {
        $this->captureCustomerReturn($request);

        if (Auth::check()) {
            return redirect()->to(Session::pull('customer_redirect_url', route('shop-new')));
        }

        return view('front.feb.theme-register');
    }

    private function captureCustomerReturn(Request $request): void
    {
        $redirect = $request->query('redirect');
        if (is_string($redirect) && str_starts_with($redirect, '/') && !str_starts_with($redirect, '//')) {
            Session::put('customer_redirect_url', $redirect);
        }

        $productId = (int) $request->query('wishlist_product_id');
        if ($productId > 0 && Product::whereKey($productId)->exists()) {
            Session::put('pending_wishlist_product_id', $productId);
        }
    }

    public function outlets()
    {
        $outlets = OutletLocation::where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('location_name')
            ->get();
        $outletPageSetting = OutletPageSetting::first();

        return view('front.feb.outlets', compact('outlets', 'outletPageSetting'));
    }

    public function thankyou_page()
    {
        $orderId = Session::get('last_order_id');

        if (!$orderId) {
            return redirect()->route('theme-carts')->with('error', 'No recent order found.');
        }

        $order = Order::join('billing_address', 'orders.billing_address_id', '=', 'billing_address.id')
            ->select('orders.*', 'orders.id as order_record_id', 'billing_address.*')
            ->where('orders.id', $orderId)
            ->first();

        if (!$order) {
            Session::forget('last_order_id');
            return redirect()->route('theme-carts')->with('error', 'No recent order found.');
        }

        $orderDetails = OrderDetail::with('product')
            ->where('order_id', $order->order_record_id)
            ->get();

        return view('front.feb.thankyou-page', compact('order', 'orderDetails'));
    }

    public function wishlist(Request $request)
    {
        if (!Auth::check()) {
            Session::put('customer_redirect_url', '/wishlist');

            return redirect()->route('theme-login')
                ->with('error', 'Please login to view your wishlist.');
        }

        $wishlists = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('front.feb.wishlist', compact('wishlists'));
    }

    public function order_tracking()
    {
        return view('front.feb.order-traking');
    }

    public function track_order(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'string', 'max:50'],
            'phone_number' => ['required', 'string', 'max:30'],
        ]);

        $phone = $this->normalizeTrackingPhone($validated['phone_number']);
        if (!preg_match('/^01[3-9]\d{8}$/', $phone)) {
            return back()->withInput()->withErrors([
                'phone_number' => 'Please enter a valid Bangladesh phone number.',
            ]);
        }

        $order = Order::with(['billingAddress', 'orderDetails.product'])
            ->where('custom_order_id', trim($validated['order_id']))
            ->first();

        $storedPhones = $order
            ? array_filter([
                $this->normalizeTrackingPhone((string) $order->order_phone_number),
                $this->normalizeTrackingPhone((string) optional($order->billingAddress)->mobile),
            ])
            : [];

        if (!$order || !in_array($phone, $storedPhones, true)) {
            return back()->withInput()->withErrors([
                'tracking' => 'No order was found with this Order ID and phone number.',
            ]);
        }

        $trackedOrder = $order;

        return view('front.feb.order-traking', compact('trackedOrder'));
    }

    private function normalizeTrackingPhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);

        return str_starts_with($phone, '8801') ? '0' . substr($phone, 3) : $phone;
    }

    public function html()
    {

        $brands = Brand::where('status', 1)->get(['brand_name', 'brand_image']);
        $blogs = Blog::where('status', 1)->orderBy('created_at', 'desc')->limit(4)->get();
        // dd($blogs);
        $sliders = Slider::where('status', 1)->get(['slider_title', 'slider_image']);
        $all_products = Product::where('status', 1)->limit(40)->get();
        $engine_oil = Product::where('status', 1)->where('category_id', 13)->limit(4)->get();
        $break_shoe = Product::where('status', 1)->where('category_id', 20)->limit(4)->get();
        $battery = Product::where('status', 1)->where('category_id', 25)->limit(4)->get();
        $top_sell = Product::where('status', 1)->orderBy('total_sell', 'desc')->limit(5)->get();

        // car care items
        $child_ids = Category::where('parent_id', 6)->pluck('id');
        $car_cares = [];

        // collect settings data
        $settings = Settings::first();

        if ($child_ids->isNotEmpty()) {
            $car_cares = Product::where('status', 1)->whereIn('category_id', $child_ids)->orderBy('created_at', 'asc')->paginate(20);
        }

        return view('front.html.index', compact('brands', 'sliders', 'top_sell', 'engine_oil', 'battery', 'break_shoe', 'blogs', 'car_cares', 'settings', 'all_products'));
    }

    public function blogs()
    {
        $blogs = Blog::where('status', 1)->orderBy('created_at', 'desc')->paginate(2);
        return view('front.html.blogs', compact('blogs'));
    }

    public function blog_details($id)
    {
        $blog = Blog::findOrFail($id);
        return view('front.html.blog_details', compact('blog'));
    }

    public function careers()
    {
        $careers = Career::where('status', 1)->orderBy('created_at', 'desc')->paginate(2);
        return view('front.html.career', compact('careers'));
    }

    public function career_details($id)
    {
        $career = Career::findOrFail($id);
        return view('front.html.career_details', compact('career'));
    }

    public function all_products()
    {
        // $products = Product::orderBy('created_at', 'desc')->paginate(config('constants.ROW_PER_PAGE'));
        $products = Product::where('status', 1)->orderBy('created_at', 'asc')->paginate(30);
        $count = Product::where('status', 1)->count();
        $page = "Products";
        return view('front.html.products', compact('products', 'count', 'page'));
    }

    public function customer_dashboard()
    {
        $recentOrders = Order::where('user_id', Auth::id())
            ->latest()
            ->limit(5)
            ->get();

        return view('front.feb.customer-dashboard', compact('recentOrders'));
    }

    public function customer_shipping_address()
    {
        return view('front.html.customer_shipping_address');
    }

    public function customer_profile()
    {
        return view('front.feb.customer-profile');
    }

    public function post_customer_profile(Request $request)
    {
        $user = Auth::user();
        $phone = preg_replace('/\D+/', '', (string) $request->phone_number);
        if (str_starts_with($phone, '8801')) {
            $phone = '0' . substr($phone, 3);
        }
        $request->merge(['phone_number' => $phone]);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'phone_number' => ['required', 'regex:/^01[3-9]\d{8}$/', Rule::unique('users', 'phone_number')->ignore($user->id)],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if (!empty($validated['password']) && !Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }

        foreach (['first_name', 'last_name', 'email', 'phone_number', 'city', 'state', 'zip', 'address'] as $field) {
            $user->{$field} = $validated[$field] ?? null;
        }
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }
        $user->save();

        return redirect()->route('customer-profile')->with('success', 'Profile updated successfully.');
    }

    public function customer_logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have successfully logged out.');
    }

    public function product_details($id_or_slug)
    {
        $product = is_numeric($id_or_slug)
            ? Product::findOrFail($id_or_slug)
            : Product::where('slug', $id_or_slug)->firstOrFail();
        return view('front.html.product_details', compact('product'));
    }

    public function contact_page()
    {
        return view('front.html.contacts');
    }

    public function about_page()
    {
        $settings = Settings::first();
        $data['data'] = $settings;
        return view('front.html.about', $data);
    }

    public function terms_and_conditions()
    {
        return view('front.html.terms_and_conditions');
    }

    public function return_policy()
    {
        return view('front.html.return_policy');
    }

    public function privacy_policy()
    {
        return view('front.html.privacy_policy');
    }

    public function faq()
    {
        $faqs = \App\Models\Faq::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('front.html.faq', compact('faqs'));
    }


    // public function track_your_order() {
    //     return view('front.html.order_track');
    // }

    public function product_category_wise($category)
    {

        $cat = explode("-", $category);
        $cat = implode(" ", $cat);
        $get_cat = Category::where('category_name', $cat)->first();
        if ($get_cat == null) {
            dd("No Category Found");
        }
        $cat_id = $get_cat->id;

        // is this category is a parent
        $child_ids = Category::where('parent_id', $cat_id)->pluck('id');
        if ($child_ids->isEmpty()) {
            $products = Product::where('status', 1)->where('category_id', $cat_id)->orderBy('created_at', 'asc')->paginate(30);
        } else {
            $products = Product::where('status', 1)->whereIn('category_id', $child_ids)->orderBy('created_at', 'asc')->paginate(30);
        }


        $count = Product::where('status', 1)->where('category_id', $cat_id)->count();
        $page = "Category: " . ucfirst($cat);
        return view('front.html.products', compact('products', 'count', 'page'));
    }

    public function product_brand_wise($brand_name)
    {
        $brand = explode("-", $brand_name);
        $brand = implode(" ", $brand);
        $get_brand = Brand::where('brand_name', $brand)->first();
        if ($get_brand == null) {
            dd("No brand found on this name");
        }
        $brand_id = $get_brand->id;
        $products = Product::where('status', 1)->where('brand_id', $brand_id)->orderBy('created_at', 'asc')->paginate(30);
        $count = Product::where('status', 1)->where('brand_id', $brand_id)->count();
        $page = "Brand: " . ucfirst($brand_name);
        return view('front.html.products', compact('products', 'count', 'page'));
    }

    public function add_to_cart($id_or_slug)
    {

        // return Session::forget('car-clinic-visitor');
        // dd(Session::get('car-clinic-visitor'));
        if (Session::get('car-clinic-visitor') == null) {

            $session_value = str_pad(mt_rand(1, 9999999999999), 10);
            Session::put('car-clinic-visitor', $session_value);
        }

        $cookie_id = Session::get('car-clinic-visitor');
        $product_data = is_numeric($id_or_slug)
            ? Product::findOrFail($id_or_slug)
            : Product::where('slug', $id_or_slug)->firstOrFail();
        $product_id = $product_data->id;
        $user_id = Auth::user() == null ? null : Auth::user()->id;
        $session_id = Auth::user() == null ? $cookie_id : null;
        $product_quantity = 1;
        $discount = 0;

        // check if product is already in cart
        $chk_cart = !empty(Auth::user()) ? Cart::where('user_id', $user_id)->where('product_id', $product_id)->first() : Cart::where('session_id', $session_id)->where('product_id', $product_id)->first();
        // dd($chk_cart);
        if (!empty($chk_cart)) {
            $chk_cart->quantity += 1;
            $chk_cart->total_price = $chk_cart->quantity * $product_data->product_value;
            $chk_cart->update();
        } else {

            $cart = new Cart;
            $cart->user_id = $user_id;
            $cart->session_id = $session_id;
            $cart->product_id = $product_id;
            $cart->product_image = $product_data->img_path;
            $cart->product_name = $product_data->name;
            $cart->unit_price = $product_data->product_value;
            $cart->quantity = $product_quantity;
            $cart->total_price = $product_quantity * $product_data->product_value;
            $cart->discount = $discount;
            $cart->final_price = $cart->total_price - $discount;
            $cart->save();
        }
        return redirect()->route('add-to-cart-details')->with('success', 'Product added to the cart successfully.');
    }

    public function add_to_cart_ajax(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'color_id' => ['nullable', 'integer', 'exists:product_colors,id'],
            'size_id' => ['nullable', 'integer', 'exists:product_sizes,id'],
        ]);

        $product = Product::with([
            'productColors' => fn ($query) => $query->where('product_colors.status', 1),
            'productSizes' => fn ($query) => $query->where('product_sizes.status', 1),
        ])->where('status', 1)->findOrFail($validated['product_id']);

        $stockQuantity = max(0, (int) $product->stock_quantity);
        if ($product->stock_status === 'Out of Stock' || $stockQuantity === 0) {
            return response()->json(['success' => false, 'message' => 'This product is out of stock.'], 422);
        }

        $selectedColor = null;
        if ($product->productColors->isNotEmpty()) {
            $selectedColor = $product->productColors->firstWhere('id', (int) ($validated['color_id'] ?? 0));
            if (!$selectedColor) {
                return response()->json(['success' => false, 'message' => 'Please select an available color.'], 422);
            }
        }

        $selectedSize = null;
        if ($product->productSizes->isNotEmpty()) {
            $selectedSize = $product->productSizes->firstWhere('id', (int) ($validated['size_id'] ?? 0));
            if (!$selectedSize) {
                return response()->json(['success' => false, 'message' => 'Please select an available size.'], 422);
            }
        }

        if (Session::get('car-clinic-visitor') === null) {
            Session::put('car-clinic-visitor', str_pad(mt_rand(1, 9999999999999), 10));
        }

        $userId = Auth::id();
        $sessionId = $userId ? null : Session::get('car-clinic-visitor');
        $colorValue = $selectedColor ? $selectedColor->name : null;
        $sizeValue = $selectedSize ? $selectedSize->name : null;

        $cartQuery = Cart::where('product_id', $product->id)
            ->where('product_color', $colorValue)
            ->where('product_size', $sizeValue);

        $userId
            ? $cartQuery->where('user_id', $userId)
            : $cartQuery->where('session_id', $sessionId);

        $cart = $cartQuery->first();
        $newQuantity = ($cart ? (int) $cart->quantity : 0) + (int) $validated['quantity'];

        if ($newQuantity > $stockQuantity) {
            return response()->json([
                'success' => false,
                'message' => "Only {$stockQuantity} item(s) are available in stock.",
            ], 422);
        }

        $regularPrice = (float) $product->product_value;
        $unitPrice = (float) $product->discount_price > 0 && (float) $product->discount_price < $regularPrice
            ? (float) $product->discount_price
            : $regularPrice;

        $cart = $cart ?: new Cart();
        $cart->user_id = $userId;
        $cart->session_id = $sessionId;
        $cart->product_id = $product->id;
        $cart->product_image = $product->img_path;
        $cart->product_name = $product->name;
        $cart->product_color = $colorValue;
        $cart->product_size = $sizeValue;
        $cart->unit_price = $unitPrice;
        $cart->quantity = $newQuantity;
        $cart->total_price = $unitPrice * $newQuantity;
        $cart->discount = 0;
        $cart->final_price = $cart->total_price;
        $cart->save();

        $countQuery = $userId
            ? Cart::where('user_id', $userId)
            : Cart::where('session_id', $sessionId);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'cart_count' => (int) $countQuery->sum('quantity'),
            'item' => [
                'id' => $cart->id,
                'quantity' => $cart->quantity,
                'color' => $cart->product_color,
                'size' => $cart->product_size,
            ],
        ]);
    }

    public function add_to_cart_details()
    {
        $session_id = Session::get('car-clinic-visitor');
        $carts = [];

        if (Auth::user() == null && $session_id != null) {
            $carts = Cart::where('session_id', $session_id)->get();
        } else if (Auth::user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        }

        return view('front.html.add_to_cart', compact('carts'));
    }

    public function update_theme_cart(Request $request, Cart $cart)
    {
        $this->ensureCartOwnership($cart);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::where('status', 1)->findOrFail($cart->product_id);
        $stockQuantity = max(0, (int) $product->stock_quantity);
        if ($product->stock_status === 'Out of Stock' || (int) $validated['quantity'] > $stockQuantity) {
            return response()->json([
                'success' => false,
                'message' => "Only {$stockQuantity} item(s) are available in stock.",
            ], 422);
        }

        $cart->quantity = (int) $validated['quantity'];
        $cart->total_price = (float) $cart->unit_price * $cart->quantity;
        $cart->final_price = $cart->total_price;
        $cart->save();

        return response()->json(array_merge([
            'success' => true,
            'message' => 'Cart updated successfully.',
            'line_total' => (float) $cart->total_price,
        ], $this->themeCartTotals()));
    }

    public function remove_theme_cart(Cart $cart)
    {
        $this->ensureCartOwnership($cart);
        $cart->delete();

        return response()->json(array_merge([
            'success' => true,
            'message' => 'Product removed from cart.',
        ], $this->themeCartTotals()));
    }

    private function ensureCartOwnership(Cart $cart): void
    {
        $owned = Auth::check()
            ? (int) $cart->user_id === (int) Auth::id()
            : $cart->session_id && $cart->session_id === Session::get('car-clinic-visitor');

        abort_unless($owned, 403, 'You cannot modify this cart item.');
    }

    private function themeCartTotals(): array
    {
        $query = Auth::check()
            ? Cart::where('user_id', Auth::id())
            : Cart::where('session_id', Session::get('car-clinic-visitor'));

        return [
            'cart_count' => (int) $query->sum('quantity'),
            'cart_subtotal' => (float) $query->sum('total_price'),
        ];
    }

    public function setCookie()
    {
        $cookie_value = str_pad(mt_rand(1, 9999999999999), 10);
        setcookie("car-clinic-visitor", $cookie_value, time() + 36000, '/');
        return;
    }

    public function getCookie(Request $request)
    {
        $user = $request->cookie('user');
        return response("User: $user");
    }

    public function checkout_page()
    {

        if (Auth::user() && Auth::user()->user_type == 'admin') {
            return redirect()->back()->with('error', 'You are logged in as an admin. As a system user, you can not checkout.');
        }

        $session_id = Session::get('car-clinic-visitor');
        $carts = [];
        if (Auth::user()) {
            $carts = Cart::where('user_id', Auth::user()->id)->get();
        } else if ($session_id != null) {
            $carts = Cart::where('session_id', $session_id)->get();
        }

        return view('front.html.checkout_page', compact('carts', 'session_id'));
    }

    public function order_success()
    {
        $orderId = Session::get('last_order_id');

        if (!$orderId) {
            return redirect()->route('checkout')->with('error', 'No recent order found.');
        }

        $order = Order::join('billing_address', 'orders.billing_address_id', '=', 'billing_address.id')
            ->select('orders.*', 'orders.id as lukaku', 'billing_address.*')
            ->where('orders.id', $orderId)
            ->first();

        if (!$order) {
            Session::forget('last_order_id');
            return redirect()->route('checkout')->with('error', 'No recent order found.');
        }

        $orderDetails = OrderDetail::with('product')->where('order_id', $order->lukaku)->get();

        return view('front.html.order_success', compact('order', 'orderDetails'));
    }

    public function track_your_order()
    {
        return view('front.html.track_your_order');
    }

    public function post_track_your_order(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'order_id' => 'required'
        ]);

        $chk_data = Order::where('custom_order_id', $request->order_id)->where('order_phone_number', $request->phone_number)->first();

        if ($chk_data == null) {
            return redirect()->back()->with('error', 'Invalid order data');
        }

        $order_id = $chk_data->id;
        $lists = OrderDetail::with('products')->where('order_id', $order_id)->get();
        // dd($lists);

        return view('front.html.post_track_your_order', compact('lists', 'chk_data'));
    }

    public function my_wishlist()
    {
        if (Auth::user()) {
            $lists = Wishlist::where('user_id', Auth::user()->id)->get();
        } else {
            $lists = Wishlist::where('session_id', Session::get('car-clinic-visitor'))->get();
        }
        // dd(Session::get('car-clinic-visitor'));

        return view('front.html.my_wishlist', compact('lists'));
    }

    public function remove_wishlist($id)
    {
        if (Auth::user()) {
            $lists = Wishlist::where('user_id', Auth::user()->id)->where('id', $id)->delete();
        } else {
            $lists = Wishlist::where('session_id', Session::get('car-clinic-visitor'))->where('id', $id)->delete();
        }
        return redirect()->route('my-wishlist')->with('success', 'Item deleted from wishlist successfully');
    }

    public function remove_from_cart($id)
    {
        if (Auth::user()) {
            $lists = Cart::where('user_id', Auth::user()->id)->where('id', $id)->delete();
        } else {
            $lists = Cart::where('session_id', Session::get('car-clinic-visitor'))->where('id', $id)->delete();
        }
        return redirect()->route('add-to-cart-details')->with('success', 'Item deleted from cart successfully');
    }

    public function add_wishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to use your wishlist.',
                'login_url' => route('theme-login'),
            ], 401);
        }

        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'success' => true,
                'status' => 'success',
                'action' => 'removed',
                'message' => 'Product removed from wishlist.',
                'count' => Wishlist::where('user_id', Auth::id())->count(),
            ]);
        }

        $product_data = Product::findOrFail($request->product_id);
        Wishlist::create([
            'user_id' => Auth::id(),
            'session_id' => null,
            'product_id' => $request->product_id,
            'unit_price' => $product_data->discount_price > 0
                ? $product_data->discount_price
                : $product_data->product_value,
            'product_image' => $product_data->img_path,
            'product_name' => $product_data->name
        ]);

        return response()->json([
            'success' => true,
            'status' => 'success',
            'action' => 'added',
            'message' => 'Product added to wishlist.',
            'count' => Wishlist::where('user_id', Auth::id())->count(),
        ]);
    }

    public function wishlist_data()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to view your wishlist.',
                'items' => [],
                'count' => 0,
            ], 401);
        }

        $productIds = Wishlist::where('user_id', Auth::id())
            ->latest()
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->values();

        return response()->json([
            'success' => true,
            'items' => $productIds,
            'count' => $productIds->count(),
        ]);
    }

    public function customer_order_history()
    {
        $id = Auth::user()->id;
        $orders = Order::join('billing_address', 'orders.billing_address_id', '=', 'billing_address.id')
            ->select('orders.id as lukaku', 'orders.*', 'billing_address.*')
            ->where('orders.user_id', $id)
            ->orderBy('orders.id', 'desc')
            ->paginate(config('constants.ROW_PER_PAGE'));
        // dd($orders);
        return view('front.html.order_history', compact('orders'));
    }

    public function customer_order_details($order_id)
    {
        $order = Order::with('billingAddress')
            ->where('custom_order_id', $order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $orderDetails = OrderDetail::with('product')->where('order_id', $order->id)->get();

        return view('front.feb.customer-order-details', compact('order', 'orderDetails'));
    }

    public function go_checkout(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        // dd($data['cart_id']);
        for ($i = 0; $i < count($data['cart_id']); $i++) {
            $cart = Cart::findorfail($data['cart_id'][$i]);
            $cart->quantity = $data['quantity'][$i];
            $cart->total_price = $cart->unit_price * $data['quantity'][$i];
            $cart->final_price = $cart->unit_price * $data['quantity'][$i];
            $cart->save();
            // dd($cart);
        }
        return redirect()->route('checkout');
    }

    public function checkout_store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'mobile' => ['required', 'regex:/^01[0-9]{9}$/'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'order_note' => ['nullable', 'string', 'max:1000'],
            'shipping_method_id' => ['nullable', 'integer', 'exists:shipping_methods,id'],
            'shipping' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['sometimes', 'string', 'in:cod,bkash'],
        ]);

        $sessionId = Session::get('car-clinic-visitor');

        if (Auth::check()) {
            $carts = Cart::where('user_id', Auth::id())->get();
        } else {
            $carts = $sessionId
                ? Cart::where('session_id', $sessionId)->get()
                : collect();
        }

        if ($carts->isEmpty()) {
            return redirect()->route('theme-carts')->with('error', 'Your cart is empty.');
        }

        if (!empty($validated['shipping_method_id'])) {
            $shippingMethod = ShippingMethod::where('status', 1)->find($validated['shipping_method_id']);

            if (!$shippingMethod) {
                return back()->withInput()->with('error', 'The selected shipping method is not available.');
            }

            $shippingCharge = (float) $shippingMethod->price;
            $shippingMethodName = $shippingMethod->name;
        } else {
            $settings = Settings::first();
            $allowedShippingCharges = collect([
                'Inside Dhaka' => (float) ($settings->charge_inside_dhaka ?? 0),
                'Outside Dhaka' => (float) ($settings->charge_outside_dhaka ?? 0),
            ]);
            $shippingCharge = (float) ($validated['shipping'] ?? -1);
            $shippingMethodName = $allowedShippingCharges->search($shippingCharge, true);

            if ($shippingMethodName === false) {
                return back()->withInput()->with('error', 'Invalid shipping charge selected.');
            }
        }

        $cartSubtotal = (float) $carts->sum('total_price');
        $paymentMethod = ($validated['payment_method'] ?? 'cod') === 'bkash'
            ? 'Bkash'
            : 'Cash on Delivery';

        DB::beginTransaction();
        try {
            $ship = new BillingAddress();
            $ship->user_id = Auth::id();
            $ship->session_id = Auth::check() ? null : $sessionId;
            $ship->first_name = $validated['first_name'];
            $ship->last_name = $request->last_name;
            $ship->company_name = $request->company_name;
            $ship->email = $request->email;
            $ship->mobile = $validated['mobile'];
            $ship->city = $request->city;
            $ship->state = $request->state;
            $ship->zip = $request->zip;
            $ship->shipping_address = $validated['shipping_address'];
            $ship->shipping_address_2 = $request->shipping_address_2;
            $ship->save();

            // dd($ship);

            // save to order table
            $order = new Order();
            $order->user_id = Auth::id();
            $order->session_id = Auth::check() ? null : $sessionId;
            $order->billing_address_id = $ship->id;
            $order->custom_order_id = $this->generateUniqueOrderId();
            $order->order_phone_number = $validated['mobile'];
            $order->total_price = $cartSubtotal;
            $order->discount = 0;
            $order->final_price = $cartSubtotal + $shippingCharge;
            // $order->coupon = $request->coupon;
            $order->payment_status = "NOT PAID";
            $order->order_note = $validated['order_note'] ?? null;
            $order->order_status = "PROCESSING";
            $order->payment_type = $paymentMethod;
            $order->delivery_charge = $shippingCharge;
            $order->shipping_method = $shippingMethodName;
            $order->possible_delivery_date = date("Y-m-d h:i:s", time() + 86400 + 86400);
            $order->save();


            // add to details page
            foreach ($carts as $cart) {
                OrderDetail::create([
                    'user_id' => Auth::id(),
                    'session_id' => Auth::check() ? null : $sessionId,
                    'product_id' => $cart->product_id,
                    'order_id' => $order->id,
                    'quantity' => $cart->quantity,
                    'unit_price' => $cart->unit_price,
                    'total' => $cart->total_price
                ]);
            }

            // destroy the cart
            if (Auth::user()) {
                Cart::where('user_id', Auth::user()->id)->delete();
            } else {
                Cart::where('session_id', $sessionId)->delete();
                Session::forget('car-clinic-visitor');
            }


            DB::commit();

            // send message
            $messages = "Welcome to " . env('APP_NAME') . ", Thanks for your order. " . $order->custom_order_id . " is your order number. Please save your order number for future tracking.";
            $phone = "88" . $request->mobile . "";
            $response = Helper::send_sms($phone, $messages);
            $last_order = Order::findOrFail($order->id);
            $last_order->sms_response = $response;
            $last_order->save();

            Session::put('last_order_id', $order->id);

            return redirect()->route('thankyou-page')->with('success', "Thanks for your order. Order submitted successfully. Your order number is " . $order->custom_order_id . " Please save your order number for future tracking");
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Transaction failed: ' . $e->getMessage();
        }
    }

    public function generateUniqueOrderId($length = 6)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $orderId = '';

        for ($i = 0; $i < $length; $i++) {
            $orderId .= $characters[random_int(0, strlen($characters) - 1)];
        }

        // Prepend a timestamp for uniqueness (optional)
        // return time() . $orderId;
        return $orderId;
    }
}
