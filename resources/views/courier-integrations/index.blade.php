@extends('layouts.master')

@section('content')
<div class="container-fluid py-6">
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div><h1 class="fs-2 fw-bolder mb-1">Courier Integration</h1><div class="text-muted">Configure external courier tools securely.</div></div>
        <a href="{{ route('orders-index') }}" class="btn btn-light-primary">Orders</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card shadow-sm border-0 mb-7">
        <div class="card-header"><div class="card-title"><h3>Fraud Checker</h3></div><span class="badge {{ $hasApiKey ? 'badge-light-success' : 'badge-light-warning' }} align-self-center">{{ $hasApiKey ? 'Configured' : 'Not configured' }}</span></div>
        <form action="{{ route('courier-integrations.update') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="mb-6"><label class="form-label required">Base URL</label><input type="url" name="fraud_checker_base_url" class="form-control" value="{{ old('fraud_checker_base_url', $settings->fraud_checker_base_url ?: $defaultUrl) }}" required><div class="form-text">Only the official BD Courier endpoint is allowed.</div>@error('fraud_checker_base_url')<div class="text-danger mt-2">{{ $message }}</div>@enderror</div>
                <div><label class="form-label {{ $hasApiKey ? '' : 'required' }}">API Key</label><input type="password" name="fraud_checker_api_key" class="form-control" placeholder="{{ $hasApiKey ? 'Leave blank to keep the current API key' : 'Enter Bearer API key' }}" {{ $hasApiKey ? '' : 'required' }} autocomplete="new-password"><div class="form-text">The API key is encrypted before it is stored and is never sent to the browser.</div>@error('fraud_checker_api_key')<div class="text-danger mt-2">{{ $message }}</div>@enderror</div>
            </div>
            <div class="card-footer text-end"><button class="btn btn-primary" type="submit">Save Configuration</button></div>
        </form>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header"><div class="card-title"><div><h3 class="mb-1">SteadFast Courier</h3><div class="text-muted fs-7">Base URL: {{ old('steadfast_base_url', $settings->steadfast_base_url ?: $steadfastDefaultUrl) }}</div></div></div><div class="d-flex align-items-center gap-3"><span class="badge {{ $hasSteadfastCredentials && $settings->steadfast_active ? 'badge-light-success' : 'badge-light-warning' }}">{{ $hasSteadfastCredentials && $settings->steadfast_active ? 'Active' : 'Inactive' }}</span><button type="button" class="btn btn-sm btn-light-success" id="checkSteadfastBalance">Check Balance</button></div></div>
        <form action="{{ route('courier-integrations.steadfast.update') }}" method="POST">
            @csrf
            <div class="card-body">
                <div id="steadfastBalanceResult" class="alert d-none"></div>
                <div class="mb-5"><label class="form-label required">Base URL</label><input type="url" name="steadfast_base_url" class="form-control" value="{{ old('steadfast_base_url', $settings->steadfast_base_url ?: $steadfastDefaultUrl) }}" required><div class="form-text">Official Packzy/SteadFast API host only.</div>@error('steadfast_base_url')<div class="text-danger">{{ $message }}</div>@enderror</div>
                <div class="row g-5">
                    <div class="col-md-6"><label class="form-label {{ $hasSteadfastCredentials ? '' : 'required' }}">API Key</label><input type="password" name="steadfast_api_key" class="form-control" placeholder="{{ $settings->steadfast_api_key ? 'API key saved. Paste a new key to replace it.' : 'Enter API key' }}" autocomplete="new-password">@error('steadfast_api_key')<div class="text-danger">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label {{ $hasSteadfastCredentials ? '' : 'required' }}">Secret Key</label><input type="password" name="steadfast_secret_key" class="form-control" placeholder="{{ $settings->steadfast_secret_key ? 'Secret key saved. Paste a new key to replace it.' : 'Enter secret key' }}" autocomplete="new-password">@error('steadfast_secret_key')<div class="text-danger">{{ $message }}</div>@enderror</div>
                    <div class="col-12"><label class="form-label {{ $hasSteadfastWebhookToken ? '' : 'required' }}">Webhook Bearer Token</label><div class="input-group"><input type="password" id="steadfastBearerToken" name="steadfast_bearer_token" class="form-control" placeholder="{{ $hasSteadfastWebhookToken ? 'Bearer token saved. Paste a new token to replace it.' : 'Enter or generate a secure token' }}" autocomplete="new-password"><button type="button" class="btn btn-light" id="toggleWebhookToken" title="Show token">👁 Show</button><button type="button" class="btn btn-light" id="copyWebhookToken" title="Copy token">⧉ Copy</button><button type="button" class="btn btn-light-primary" id="generateWebhookToken">Generate</button></div><div class="form-text">Webhook URL: <code>{{ route('steadfast.webhook') }}</code>. Saved encrypted tokens are not re-exposed; paste or generate a new one to view/copy.</div>@error('steadfast_bearer_token')<div class="text-danger">{{ $message }}</div>@enderror</div>
                </div>
                <div class="form-check form-switch mt-6"><input type="hidden" name="steadfast_active" value="0"><input class="form-check-input" type="checkbox" name="steadfast_active" value="1" id="steadfastActive" @checked(old('steadfast_active', $settings->steadfast_active))><label class="form-check-label" for="steadfastActive">Active SteadFast Courier</label></div>
            </div>
            <div class="card-footer d-flex justify-content-between"><button type="submit" class="btn btn-success">Save SteadFast Credentials</button><button type="submit" form="deleteSteadfastForm" class="btn btn-light-danger" onclick="return confirm('Delete all SteadFast credentials?')">Delete Credentials</button></div>
        </form>
        <form id="deleteSteadfastForm" action="{{ route('courier-integrations.steadfast.delete') }}" method="POST">@csrf @method('DELETE')</form>
    </div>
</div>
@endsection

@section('endScript')
<script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
<script>
document.getElementById('generateWebhookToken').addEventListener('click',function(){const bytes=new Uint8Array(32);crypto.getRandomValues(bytes);document.getElementById('steadfastBearerToken').value=Array.from(bytes,b=>b.toString(16).padStart(2,'0')).join('')});
document.getElementById('toggleWebhookToken').addEventListener('click',function(){const input=document.getElementById('steadfastBearerToken');const showing=input.type==='text';input.type=showing?'password':'text';this.textContent=showing?'👁 Show':'◉ Hide';this.title=showing?'Show token':'Hide token'});
document.getElementById('copyWebhookToken').addEventListener('click',async function(){const input=document.getElementById('steadfastBearerToken');if(!input.value){const original=this.textContent;this.textContent='No new token';setTimeout(()=>this.textContent=original,1500);return}try{if(navigator.clipboard&&window.isSecureContext)await navigator.clipboard.writeText(input.value);else{input.select();document.execCommand('copy');input.setSelectionRange(0,0)}const original=this.textContent;this.textContent='✓ Copied';setTimeout(()=>this.textContent=original,1500)}catch(error){alert('Could not copy the token. Please copy it manually.')}});
document.getElementById('checkSteadfastBalance').addEventListener('click',async function(){const box=document.getElementById('steadfastBalanceResult');this.disabled=true;box.className='alert alert-info';box.textContent='Checking SteadFast balance…';try{const response=await axios.get(@json(route('courier-integrations.steadfast.balance')),{headers:{Accept:'application/json','X-Requested-With':'XMLHttpRequest'}});const balance=response.data.current_balance ?? response.data.balance ?? response.data.data?.current_balance;box.className='alert alert-success';box.textContent=balance!==undefined?'Current balance: ৳'+Number(balance).toLocaleString('en-US'):JSON.stringify(response.data)}catch(error){box.className='alert alert-danger';box.textContent=error.response?.data?.message||'Unable to check balance.'}finally{this.disabled=false}});
</script>
@endsection
