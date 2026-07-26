@extends('front.feb.layouts.master')

@section('title', 'Customer Registration')

@section('content')
<style>
    .customer-auth-page { min-height: 68vh; padding: 55px 16px 80px; background: #f5f6f8; color: #182033; }
    .customer-auth-card { width: min(500px, 100%); margin: 0 auto; padding: 34px; background: #fff; border: 1px solid #e1e5ea; border-radius: 8px; box-shadow: 0 8px 28px rgba(15, 23, 42, .07); }
    .customer-auth-card h1 { margin: 0 0 8px; font-size: 27px; font-weight: 700; text-align: center; }
    .customer-auth-subtitle { margin: 0 0 28px; color: #687386; font-size: 14px; text-align: center; }
    .customer-auth-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 14px; }
    .customer-auth-field { margin-bottom: 18px; }
    .customer-auth-field.full { grid-column: 1 / -1; }
    .customer-auth-field label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 600; }
    .customer-auth-field input { width: 100%; height: 46px; padding: 0 13px; border: 1px solid #cfd5dd; border-radius: 4px; background: #fff; font-size: 15px; }
    .customer-auth-field input:focus { outline: 0; border-color: #172033; box-shadow: 0 0 0 3px rgba(23, 32, 51, .08); }
    .customer-auth-submit { width: 100%; height: 48px; border: 0; border-radius: 4px; background: #172033; color: #fff; font-size: 15px; font-weight: 700; cursor: pointer; }
    .customer-auth-submit:hover { background: #27344c; }
    .customer-auth-switch { margin: 22px 0 0; text-align: center; color: #687386; font-size: 14px; }
    .customer-auth-switch a { color: #e2305c; font-weight: 700; text-decoration: none; }
    .customer-auth-alert { margin-bottom: 20px; padding: 12px 14px; border-radius: 4px; color: #8d2020; background: #fff0f0; border: 1px solid #f3caca; font-size: 14px; }
    .customer-auth-errors { margin: 0; padding-left: 18px; }
    @media (max-width: 575px) { .customer-auth-page { padding: 28px 14px 55px; } .customer-auth-card { padding: 25px 20px; } .customer-auth-grid { grid-template-columns: 1fr; } .customer-auth-field.full { grid-column: auto; } }
</style>

<main class="customer-auth-page">
    <section class="customer-auth-card">
        <h1>Create Account</h1>
        <p class="customer-auth-subtitle">Register to save products in your wishlist.</p>

        @if(isset($errors) && $errors->any())
            <div class="customer-auth-alert">
                <ul class="customer-auth-errors">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('user.register.post') }}">
            @csrf
            <div class="customer-auth-grid">
                <div class="customer-auth-field">
                    <label for="register-first-name">First Name</label>
                    <input id="register-first-name" name="first_name" type="text" value="{{ old('first_name') }}" autocomplete="given-name" required autofocus>
                </div>
                <div class="customer-auth-field">
                    <label for="register-last-name">Last Name</label>
                    <input id="register-last-name" name="last_name" type="text" value="{{ old('last_name') }}" autocomplete="family-name" required>
                </div>
                <div class="customer-auth-field full">
                    <label for="register-phone">Phone Number</label>
                    <input id="register-phone" name="phone_number" type="tel" value="{{ old('phone_number') }}" placeholder="01XXXXXXXXX" autocomplete="tel" required>
                </div>
                <div class="customer-auth-field">
                    <label for="register-password">Password</label>
                    <input id="register-password" name="password" type="password" minlength="6" autocomplete="new-password" required>
                </div>
                <div class="customer-auth-field">
                    <label for="register-password-confirmation">Confirm Password</label>
                    <input id="register-password-confirmation" name="password_confirmation" type="password" minlength="6" autocomplete="new-password" required>
                </div>
            </div>
            <button class="customer-auth-submit" type="submit">Create Account</button>
        </form>

        <p class="customer-auth-switch">Already have an account? <a href="{{ route('theme-login') }}">Login</a></p>
    </section>
</main>
@endsection
