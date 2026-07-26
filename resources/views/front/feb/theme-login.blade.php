@extends('front.feb.layouts.master')

@section('title', 'Customer Login')

@section('content')
<style>
    .customer-auth-page { min-height: 68vh; padding: 55px 16px 80px; background: #f5f6f8; color: #182033; }
    .customer-auth-card { width: min(460px, 100%); margin: 0 auto; padding: 34px; background: #fff; border: 1px solid #e1e5ea; border-radius: 8px; box-shadow: 0 8px 28px rgba(15, 23, 42, .07); }
    .customer-auth-card h1 { margin: 0 0 8px; font-size: 27px; font-weight: 700; text-align: center; }
    .customer-auth-subtitle { margin: 0 0 28px; color: #687386; font-size: 14px; text-align: center; }
    .customer-auth-field { margin-bottom: 18px; }
    .customer-auth-field label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 600; }
    .customer-auth-field input { width: 100%; height: 46px; padding: 0 13px; border: 1px solid #cfd5dd; border-radius: 4px; background: #fff; font-size: 15px; }
    .customer-auth-field input:focus { outline: 0; border-color: #172033; box-shadow: 0 0 0 3px rgba(23, 32, 51, .08); }
    .customer-auth-submit { width: 100%; height: 48px; border: 0; border-radius: 4px; background: #172033; color: #fff; font-size: 15px; font-weight: 700; cursor: pointer; }
    .customer-auth-submit:hover { background: #27344c; }
    .customer-auth-switch { margin: 22px 0 0; text-align: center; color: #687386; font-size: 14px; }
    .customer-auth-switch a { color: #e2305c; font-weight: 700; text-decoration: none; }
    .customer-auth-alert { margin-bottom: 20px; padding: 12px 14px; border-radius: 4px; font-size: 14px; }
    .customer-auth-alert.error { color: #8d2020; background: #fff0f0; border: 1px solid #f3caca; }
    .customer-auth-alert.success { color: #116235; background: #edf9f2; border: 1px solid #bfe6cf; }
    .customer-auth-errors { margin: 0; padding-left: 18px; }
    @media (max-width: 575px) { .customer-auth-page { padding: 28px 14px 55px; } .customer-auth-card { padding: 25px 20px; } }
</style>

<main class="customer-auth-page">
    <section class="customer-auth-card">
        <h1>Customer Login</h1>
        <p class="customer-auth-subtitle">Login to add and manage your wishlist.</p>

        @if(session('success'))
            <div class="customer-auth-alert success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="customer-auth-alert error">{{ session('error') }}</div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="customer-auth-alert error">
                <ul class="customer-auth-errors">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('user.login.post') }}">
            @csrf
            <div class="customer-auth-field">
                <label for="login-phone">Phone Number</label>
                <input id="login-phone" name="phone_number" type="tel" value="{{ old('phone_number') }}" placeholder="01XXXXXXXXX" autocomplete="tel" required autofocus>
            </div>
            <div class="customer-auth-field">
                <label for="login-password">Password</label>
                <input id="login-password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required>
            </div>
            <button class="customer-auth-submit" type="submit">Login</button>
        </form>

        <p class="customer-auth-switch">Don't have an account? <a href="{{ route('theme-register') }}">Create account</a></p>
    </section>
</main>
@endsection
