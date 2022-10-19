<h3 class="px-2 py-3 text-s font-semibold text-gray-500 uppercase tracking-wider">
    Dashboard
</h3>

<a href="{{ route('home') }}"
   class="{{ Route::is('home') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <svg
        class="{{ Route::is('home') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }} mr-3 h-6 w-6"
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Home
</a>

<a href="{{ route('home.profile') }}"
   class="{{ Route::is('home.profile') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <svg
        class="{{ Route::is('home.profile') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }} mr-3 h-6 w-6"
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Profile
</a>

<h3 class="px-2 py-3 text-s font-semibold text-gray-500 uppercase tracking-wider">
    Billing
</h3>

<a href="{{ route('home.subscription') }}"
   class="{{ Route::is('home.subscription') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <svg
        class="{{ Route::is('home.subscription') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }} mr-3 h-6 w-6"
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
    </svg>
    Subscription
</a>

@admin
<h3 class="px-2 py-3 text-s font-semibold text-gray-500 uppercase tracking-wider">
    Administrator
</h3>

<a href="{{ route('admin.users') }}"
   class="{{ Route::is('admin.users') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <svg
        class="{{ Route::is('admin.users') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }} mr-3 h-6 w-6"
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>
    Users
    <span class="bg-gray-900 group-hover:bg-gray-800 ml-auto inline-block py-0.5 px-3 text-xs font-medium rounded-full">
        {{ \App\Models\User::count() }}
    </span>
</a>

<div class="space-y-1">
    <button type="button"
            class="text-gray-300 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md w-full">
        <svg class="text-gray-400 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Files
    </button>
    <div class="space-y-1">
        <a href="{{ route('admin.versions') }}"
           class="{{ Route::is('admin.versions') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium rounded-md">
            Versions
            <span class="bg-gray-900 group-hover:bg-gray-800 ml-auto inline-block py-0.5 px-3 text-xs font-medium rounded-full">
                {{ \App\Models\Version::count() }}
            </span>
        </a>
        <a href="{{ route('admin.launcher') }}"
           class="{{ Route::is('admin.launcher') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium rounded-md">
            Launcher & Assets
        </a>
        <a href="{{ route('admin.loader') }}"
           class="{{ Route::is('admin.loader') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium rounded-md">
            Loader
        </a>
    </div>
</div>

<a href="{{ route('admin.referrals') }}"
   class="{{ Route::is('admin.referrals') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <svg
        class="{{ Route::is('admin.referrals') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }} mr-3 h-6 w-6"
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
    </svg>
    Referral Codes
    <span class="bg-gray-900 group-hover:bg-gray-800 ml-auto inline-block py-0.5 px-3 text-xs font-medium rounded-full">
        {{ \App\Models\ReferralCode::count() }}
    </span>
</a>

<a href="{{ route('admin.license-requests') }}"
   class="{{ Route::is('admin.license-requests') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <svg
        class="{{ Route::is('admin.license-requests') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }} mr-3 h-6 w-6"
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
    </svg>
    License Requests
    <span class="bg-gray-900 group-hover:bg-gray-800 ml-auto inline-block py-0.5 px-3 text-xs font-medium rounded-full">
        {{ \App\Models\LicenseRequest::where('status', \App\Enums\LicenseRequest::PENDING->value)->count() }}
    </span>
</a>
@endadmin
