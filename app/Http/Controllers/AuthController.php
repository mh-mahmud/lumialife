<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Product;

class AuthController extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            return in_array(Auth::user()->user_type, ['admin', 'agent'], true)
                ? redirect()->route('dashboard')
                : redirect()->route('customer-dashboard');
        } else {
            return view('auth.login');
        }
    }

    public function user_login() {
        if (Auth::check()) {
            return redirect()->route('customer-dashboard');
        } else {
            return view('auth.user_login');
        }
    }

    public function user_register() {
        if (Auth::check()) {
            return redirect()->route('customer-dashboard');
        } else {
            return view('auth.user_register');
        }
    }


    public function register(Request $request)
    {
        //var_dump($request);die();
        $inputs = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'first_name' => $inputs['first_name'],
            'last_name' => $inputs['first_name'],
            'email' => $inputs['email'],
            'username' => $inputs['username'],
            'password' => bcrypt($inputs['password'])
        ]);
        $token = $user->createToken('gpleCRMToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    public function postLogin_backup(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log in with the provided credentials
        if (Auth::attempt($request->only('email', 'password'), $request->has('remember'))) {
            // Redirect to a specific route or homepage on successful login
            return redirect()->intended('/dashboard');
        }

        // If authentication fails, redirect back with errors
        return redirect()->back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function postLogin(Request $request)
    {
        //Check user is already logged in
        //dd($request);die();
        if (session()->has('users')) {
            return redirect('dashboard')->with('success', 'You are already logged in.');
        }

        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        // The back-office login is exclusively for active admins and agents.
        $user = User::query()
            ->where('email', $request->email)
            ->where('status', 1)
            ->whereIn('user_type', ['admin', 'agent'])
            ->first();

        if (empty($user)) {
            return redirect()->route('login')->withInput($request->only('email'))
                ->with('error', 'Only active Admin or Agent accounts can log in here.');
        }

        if (Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            session()->regenerate();
            session()->put('users', Auth::user());
            return redirect()->intended('dashboard')->with('success', 'You have successfully logged in.');
        }

        return redirect()->route('login')->withInput($request->only('email'))->with('error', 'Invalid password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login')->with('success', 'You have successfully logged out.');
    }

    public function postUserRegister(Request $request)
    {
        $request->merge([
            'phone_number' => $this->normalizePhone($request->phone_number),
        ]);

        $inputs = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'regex:/^(?:\+8801|01)[3-9]\d{8}$/', 'unique:users,phone_number'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'first_name' => $inputs['first_name'],
            'last_name' => $inputs['last_name'],
            'phone_number' => $inputs['phone_number'],
            'password' => bcrypt($inputs['password']),
            'user_type' => 'customer',
            'status' => 1
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('customers', $user);
        $this->storePendingWishlist($user);

        return redirect()->to(Session::pull('customer_redirect_url', route('shop-new')))
            ->with('success', 'Your account has been created successfully.');
    }

    public function postUserLogin(Request $request)
    {
        $request->merge([
            'phone_number' => $this->normalizePhone($request->phone_number),
        ]);

        $this->validate($request, [
            'phone_number' => ['required', 'regex:/^01[3-9]\d{8}$/'],
            'password' => ['required', 'string'],
        ]);

        //If the user is inactive
        $user = User::where('phone_number', $request->phone_number)->where('status', 1)->where('user_type', 'customer')->first();
        if (empty($user)) {
            return redirect()->route('theme-login')->withInput()->with('error', 'No active customer account was found with this phone number.');
        }

        $credentials = $request->only('phone_number', 'password');

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            session()->put('customers', Auth::user());
            $this->storePendingWishlist(Auth::user());

            return redirect()->to(Session::pull('customer_redirect_url', route('shop-new')))
                ->with('success', 'You have successfully logged in.');
        }
        //If the phone_number is correct but password is wrong
        $user = User::where('phone_number', $request->phone_number)->first();
        if (empty($user)) {
            return redirect()->route('theme-login')->withInput()->with('error', 'Invalid phone number.');
        }

        return redirect()->route('theme-login')->withInput()->with('error', 'Invalid password.');
    }

    private function normalizePhone(?string $phone): string
    {
        $phone = preg_replace('/\D+/', '', (string) $phone);

        if (str_starts_with($phone, '8801')) {
            return '0' . substr($phone, 3);
        }

        return $phone;
    }

    private function storePendingWishlist(User $user): void
    {
        $productId = (int) Session::pull('pending_wishlist_product_id');
        if ($productId < 1) {
            return;
        }

        $product = Product::where('status', 1)->find($productId);
        if (!$product) {
            return;
        }

        Wishlist::firstOrCreate(
            ['user_id' => $user->id, 'product_id' => $product->id],
            [
                'session_id' => null,
                'product_name' => $product->name,
                'product_image' => $product->img_path,
                'unit_price' => $product->discount_price > 0
                    ? $product->discount_price
                    : $product->product_value,
            ]
        );
    }
}
