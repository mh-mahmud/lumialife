@extends('front.feb.layouts.master')

@section('title', 'Edit Profile')

@section('content')
<style>
    .profile-page { min-height: 72vh; padding: 35px 16px 75px; background: #f4f6f9; color: #182033; }
    .profile-container { width: min(900px, 100%); margin: 0 auto; }
    .account-page-header { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 22px; }
    .account-page-header h1 { margin: 0 0 5px; font-size: 26px; font-weight: 700; }
    .account-page-header p { margin: 0; color: #687386; font-size: 13px; }
    .account-back { color: #29364b; font-size: 13px; font-weight: 600; text-decoration: none; }
    .profile-card { overflow: hidden; border: 1px solid #dfe3e8; border-radius: 11px; background: #fff; box-shadow: 0 4px 16px rgba(22,34,54,.06); }
    .profile-card-heading { padding: 19px 24px; border-bottom: 1px solid #e5e8ec; }
    .profile-card-heading h2 { margin: 0; font-size: 18px; font-weight: 700; }
    .profile-form { padding: 25px 24px; }
    .profile-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 19px; }
    .profile-field.full { grid-column: 1 / -1; }
    .profile-field label { display: block; margin-bottom: 7px; font-size: 12px; font-weight: 700; }
    .profile-field input, .profile-field textarea { width: 100%; border: 1px solid #ced4dc; border-radius: 6px; background: #fff; color: #172033; font-size: 14px; }
    .profile-field input { height: 45px; padding: 0 13px; }
    .profile-field textarea { min-height: 105px; padding: 12px 13px; resize: vertical; }
    .profile-field input:focus, .profile-field textarea:focus { outline: 0; border-color: #29364b; box-shadow: 0 0 0 3px rgba(41,54,75,.08); }
    .profile-error { display: block; margin-top: 5px; color: #d92d20; font-size: 11px; }
    .password-section { grid-column: 1 / -1; margin-top: 4px; padding-top: 22px; border-top: 1px solid #e7e9ed; }
    .password-section h3 { margin: 0 0 4px; font-size: 16px; }
    .password-section p { margin: 0 0 17px; color: #7a8494; font-size: 12px; }
    .password-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
    .profile-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; }
    .profile-cancel, .profile-save { display: inline-flex; align-items: center; justify-content: center; min-height: 43px; padding: 0 21px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none; }
    .profile-cancel { border: 1px solid #ccd2da; color: #374151; background: #fff; }
    .profile-save { border: 0; color: #fff; background: #202938; cursor: pointer; }
    .profile-alert { margin-bottom: 18px; padding: 13px 15px; border-radius: 6px; font-size: 13px; }
    .profile-alert.success { border: 1px solid #b9e1c9; background: #edf9f2; color: #146c3b; }
    .profile-alert.error { border: 1px solid #f0c4c4; background: #fff1f1; color: #9b2929; }
    .profile-alert ul { margin: 0; padding-left: 18px; }
    @media (max-width: 700px) { .profile-grid, .password-grid { grid-template-columns: 1fr; } .profile-field.full, .password-section { grid-column: auto; } }
    @media (max-width: 575px) { .profile-page { padding: 24px 11px 60px; } .account-page-header { align-items: flex-start; } .profile-form { padding: 20px 15px; } .profile-card-heading { padding: 16px; } .profile-actions { flex-direction: column-reverse; } .profile-cancel, .profile-save { width: 100%; } }
</style>

<main class="profile-page">
    <div class="profile-container">
        <header class="account-page-header">
            <div><h1>Edit Profile</h1><p>Update your personal information and password</p></div>
            <a class="account-back" href="{{ route('customer-dashboard') }}"><i class="fa fa-arrow-left"></i> Dashboard</a>
        </header>

        @if(session('success'))<div class="profile-alert success">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="profile-alert error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <section class="profile-card">
            <div class="profile-card-heading"><h2>Account Information</h2></div>
            <form class="profile-form" action="{{ route('post-customer-profile') }}" method="POST">
                @csrf
                <div class="profile-grid">
                    <div class="profile-field">
                        <label for="profile-first-name">First Name</label>
                        <input id="profile-first-name" type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" required>
                        @error('first_name')<span class="profile-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="profile-field">
                        <label for="profile-last-name">Last Name</label>
                        <input id="profile-last-name" type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" required>
                        @error('last_name')<span class="profile-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="profile-field">
                        <label for="profile-phone">Phone Number</label>
                        <input id="profile-phone" type="tel" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}" required>
                        @error('phone_number')<span class="profile-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="profile-field">
                        <label for="profile-email">Email Address</label>
                        <input id="profile-email" type="email" name="email" value="{{ old('email', Auth::user()->email) }}">
                        @error('email')<span class="profile-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="profile-field">
                        <label for="profile-city">City</label>
                        <input id="profile-city" type="text" name="city" value="{{ old('city', Auth::user()->city) }}">
                    </div>
                    <div class="profile-field">
                        <label for="profile-state">State / District</label>
                        <input id="profile-state" type="text" name="state" value="{{ old('state', Auth::user()->state) }}">
                    </div>
                    <div class="profile-field">
                        <label for="profile-zip">Postal Code</label>
                        <input id="profile-zip" type="text" name="zip" value="{{ old('zip', Auth::user()->zip) }}">
                    </div>
                    <div class="profile-field full">
                        <label for="profile-address">Address</label>
                        <textarea id="profile-address" name="address">{{ old('address', Auth::user()->address) }}</textarea>
                    </div>

                    <section class="password-section">
                        <h3>Change Password</h3>
                        <p>Leave these fields blank if you do not want to change your password.</p>
                        <div class="password-grid">
                            <div class="profile-field">
                                <label for="current-password">Current Password</label>
                                <input id="current-password" type="password" name="current_password" autocomplete="current-password">
                                @error('current_password')<span class="profile-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="profile-field">
                                <label for="new-password">New Password</label>
                                <input id="new-password" type="password" name="password" minlength="6" autocomplete="new-password">
                                @error('password')<span class="profile-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="profile-field">
                                <label for="confirm-password">Confirm Password</label>
                                <input id="confirm-password" type="password" name="password_confirmation" minlength="6" autocomplete="new-password">
                            </div>
                        </div>
                    </section>
                </div>
                <div class="profile-actions">
                    <a class="profile-cancel" href="{{ route('customer-dashboard') }}">Cancel</a>
                    <button class="profile-save" type="submit">Save Changes</button>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection
