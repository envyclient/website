@extends('layouts.guest')

@section('body')
    <div class="min-h-screen bg-white">
        <header>
            <div class="relative bg-white" x-data="{ open: false }">
                <div
                    class="flex justify-between items-center max-w-7xl mx-auto px-4 py-6 sm:px-6 md:justify-start md:space-x-10 lg:px-8">
                    <div class="flex justify-start lg:w-0 lg:flex-1">
                        <img class="h-8 w-auto sm:h-10" src="{{ asset('logo.svg') }}" alt="envy logo">
                    </div>
                    <div class="-mr-2 -my-2 md:hidden">
                        <button
                            type="button"
                            @click="open = true"
                            class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gray-500"
                        >
                            <!-- Heroicon name: outline/menu -->
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                    <nav class="hidden md:flex space-x-10">
                        <a href="#home" class="text-base font-medium text-gray-500 hover:text-gray-900">
                            Home
                        </a>
                        <a href="#features" class="text-base font-medium text-gray-500 hover:text-gray-900">
                            Features
                        </a>
                        <a href="#pricing" class="text-base font-medium text-gray-500 hover:text-gray-900">
                            Pricing
                        </a>
                        <a href="#team" class="text-base font-medium text-gray-500 hover:text-gray-900">
                            Team
                        </a>
                    </nav>
                    <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0">
                        @guest
                            <a href="{{ route('login') }}"
                               class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                               class="ml-8 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700">
                                Register
                            </a>
                        @else
                            <a href="{{ route('home') }}"
                               class="ml-8 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700">
                                Home
                            </a>
                        @endguest
                    </div>
                </div>

                {{--Mobile Menu--}}
                <div
                    class="absolute z-30 top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden"
                    x-show="open"
                    x-transition:enter="duration-200 ease-out"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="duration-100 ease-in"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                >
                    <div
                        class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50">
                        <div class="pt-5 pb-6 px-5">
                            <div class="flex items-center justify-between">
                                <div class="p-2">
                                    <img class="h-10 w-auto sm:h-20" src="{{asset('logo.svg')}}" alt="envy logo">
                                </div>
                                <div class="-mr-2">
                                    <button
                                        type="button"
                                        @click="open = false"
                                        class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gray-500"
                                    >
                                        <!-- Heroicon name: outline/x -->
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="py-6 px-5">
                            <div class="grid grid-cols-2 gap-4">
                                <a href="#home" class="text-base font-medium text-gray-500 hover:text-gray-900">
                                    Home
                                </a>
                                <a href="#features" class="text-base font-medium text-gray-500 hover:text-gray-900">
                                    Features
                                </a>
                                <a href="#pricing" class="text-base font-medium text-gray-500 hover:text-gray-900">
                                    Pricing
                                </a>
                                <a href="#team" class="text-base font-medium text-gray-500 hover:text-gray-900">
                                    Team
                                </a>
                            </div>
                            <div class="mt-6">
                                @guest
                                    <a href="{{ route('register') }}"
                                       class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700">
                                        Register
                                    </a>
                                    <p class="mt-6 text-center text-base font-medium text-gray-500">
                                        Existing customer?
                                        <a href="{{ route('login') }}" class="text-gray-900">
                                            Login
                                        </a>
                                    </p>
                                @else
                                    <p class="mt-6 text-center text-base font-medium text-gray-500">
                                        <a href="{{ route('home') }}" class="text-gray-900">
                                            Home
                                        </a>
                                    </p>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <main>

            {{-- Hero Section--}}
            <section class="relative" id="home">
                <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gray-100"></div>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="relative shadow-xl sm:rounded-2xl sm:overflow-hidden">
                        <div class="absolute inset-0">
                            <img class="h-full w-full object-cover filter blur-sm"
                                 src="{{ asset('assets/hero.png') }}"
                                 alt="minecraft background">
                            <div class="absolute inset-0 bg-green-700" style="mix-blend-mode: multiply;"></div>
                        </div>
                        <div class="relative px-4 py-16 sm:px-6 sm:py-24 lg:py-32 lg:px-8">
                            <h1 class="text-center text-4xl font-extrabold block text-white tracking-tight sm:text-5xl lg:text-6xl">
                                Envy <span class="text-gray-400">Client</span>
                            </h1>
                            <p class="mt-6 max-w-lg mx-auto text-center text-xl text-gray-200 sm:max-w-3xl">
                                Envy is a premium Minecraft Client with the best features on the market.
                            </p>
                            <div class="mt-10 max-w-sm mx-auto sm:max-w-none sm:flex sm:justify-center">
                                <div
                                    class="space-y-4 sm:space-y-0 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-5">
                                    @guest
                                        <a href="{{ route('login') }}"
                                           class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 sm:px-8">
                                            Login
                                        </a>
                                    @else
                                        <a href="{{ route('home') }}"
                                           class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 sm:px-8">
                                            Home
                                        </a>
                                    @endif
                                    <a href="{{ config('services.discord.invite') }}"
                                       class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-500 bg-opacity-60 hover:bg-opacity-70 sm:px-8">
                                            <span class="pr-2">
                                               <svg width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.552 6.712c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888.008-.488-.36-.888-.816-.888zm2.92 0c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888s-.36-.888-.816-.888z"/>
                                                    <path
                                                        d="M13.36 0H2.64C1.736 0 1 .736 1 1.648v10.816c0 .912.736 1.648 1.64 1.648h9.072l-.424-1.48 1.024.952.968.896L15 16V1.648C15 .736 14.264 0 13.36 0zm-3.088 10.448s-.288-.344-.528-.648c1.048-.296 1.448-.952 1.448-.952-.328.216-.64.368-.92.472-.4.168-.784.28-1.16.344a5.604 5.604 0 0 1-2.072-.008 6.716 6.716 0 0 1-1.176-.344 4.688 4.688 0 0 1-.584-.272c-.024-.016-.048-.024-.072-.04-.016-.008-.024-.016-.032-.024-.144-.08-.224-.136-.224-.136s.384.64 1.4.944c-.24.304-.536.664-.536.664-1.768-.056-2.44-1.216-2.44-1.216 0-2.576 1.152-4.664 1.152-4.664 1.152-.864 2.248-.84 2.248-.84l.08.096c-1.44.416-2.104 1.048-2.104 1.048s.176-.096.472-.232c.856-.376 1.536-.48 1.816-.504.048-.008.088-.016.136-.016a6.521 6.521 0 0 1 4.024.752s-.632-.6-1.992-1.016l.112-.128s1.096-.024 2.248.84c0 0 1.152 2.088 1.152 4.664 0 0-.68 1.16-2.448 1.216z"/>
                                                </svg>
                                            </span>
                                        Discord
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Features Section--}}
            <section class="py-16 bg-gray-50 overflow-hidden lg:py-24" id="features">
                <div class="relative max-w-xl mx-auto px-4 sm:px-6 lg:px-8 lg:max-w-7xl">
                    <div class="relative">
                        <div
                            class="lg:mx-auto lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-2 lg:grid-flow-col-dense lg:gap-24">
                            <div class="px-4 max-w-xl mx-auto sm:px-6 lg:py-16 lg:max-w-none lg:mx-0 lg:px-0">
                                <div>
                                    <div class="text-white">
                                        <span
                                            class="h-12 w-12 rounded-md flex items-center justify-center bg-green-600">
                                           <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="mt-6">
                                        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                                            Account Manager
                                        </h2>
                                        <p class="mt-4 text-lg text-gray-600">
                                            Envy offers a modern and sleek-looking Account Manager with a built-in
                                            multiplayer screen, allowing you to instantly connect to a wanted server.
                                        </p>
                                        <br>
                                        <hr>
                                        <p class="mt-2 text-lg text-gray-600">
                                            The account manager also has a built in <a
                                                href="https://thealtening.com/" class="text-blue-500">The
                                                Altening</a> support
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('home.subscription') }}"
                                               class="inline-flex px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                                Subscribe
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-12 sm:mt-16 lg:mt-0">
                                <div class="pl-4 -mr-48 sm:pl-6 md:-mr-16 lg:px-0 lg:m-0 lg:relative lg:h-full">
                                    <img
                                        class="w-full rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 lg:absolute lg:left-0 lg:h-full lg:w-auto lg:max-w-none"
                                        src="{{ asset('assets/features/menu.png') }}"
                                        alt="envy client account manager">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-24">
                        <div
                            class="lg:mx-auto lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-2 lg:grid-flow-col-dense lg:gap-24">
                            <div
                                class="px-4 max-w-xl mx-auto sm:px-6 lg:py-32 lg:max-w-none lg:mx-0 lg:px-0 lg:col-start-2">
                                <div>
                                    <div class="text-white">
                                        <span
                                            class="h-12 w-12 rounded-md flex items-center justify-center bg-green-600">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                  <path stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="mt-6">
                                        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                                            ClickGui
                                        </h2>
                                        <p class="mt-4 text-lg text-gray-600">
                                            ClickGui for the client is built on the idea of it being easy to use; it
                                            employs the old school style but with a modern twist that includes a Web
                                            Config window and a Console window
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('home.subscription') }}"
                                               class="inline-flex px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                                Subscribe
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-12 sm:mt-16 lg:mt-0">
                                <div class="pr-4 -ml-48 sm:pr-6 md:-ml-16 lg:px-0 lg:m-0 lg:relative lg:h-full">
                                    <img
                                        class="w-full rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 lg:absolute lg:right-0 lg:h-full lg:w-auto lg:max-w-none"
                                        src="{{ asset('assets/features/client.png') }}"
                                        alt="envy client ui">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-24 relative">
                        <div
                            class="lg:mx-auto lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-2 lg:grid-flow-col-dense lg:gap-24">
                            <div class="px-4 max-w-xl mx-auto sm:px-6 lg:py-16 lg:max-w-none lg:mx-0 lg:px-0">
                                <div>
                                    <div class="text-white">
                                        <span
                                            class="h-12 w-12 rounded-md flex items-center justify-center bg-green-600">
                                           <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="mt-6">
                                        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                                            Custom Launcher
                                        </h2>
                                        <p class="mt-4 text-lg text-gray-600">
                                            Envy comes with a custom launcher written using C++ to provide the smoothest
                                            possible experience.
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('home.subscription') }}"
                                               class="inline-flex px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                                Subscribe
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-12 sm:mt-16 lg:mt-0">
                                <div class="pl-4 -mr-48 sm:pl-6 md:-mr-16 lg:px-0 lg:m-0 lg:relative lg:h-full">
                                    <img
                                        class="w-full rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 lg:absolute lg:left-0 lg:h-full lg:w-auto lg:max-w-none"
                                        src="{{ asset('assets/features/launcher.png') }}"
                                        alt="envy client launcher">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Pricing Section --}}
            <section class="bg-gray-800" id="pricing">
                <div class="pt-12 sm:pt-16 lg:pt-24">
                    <div class="max-w-7xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                        <div class="max-w-3xl mx-auto space-y-2 lg:max-w-none">
                            <h2 class="text-lg leading-6 font-semibold text-gray-300 uppercase tracking-wider">
                                Pricing
                            </h2>
                            <p class="text-3xl font-extrabold text-white sm:text-4xl lg:text-5xl">
                                Premium platform with premium features
                            </p>
                            <p class="text-xl text-gray-300">
                                We offer access to a premium cheat with a sleek design
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pb-12 bg-gray-50 sm:mt-12 sm:pb-16 lg:mt-16 lg:pb-24">
                    <div class="relative">
                        <div class="absolute inset-0 h-3/4 bg-gray-800"></div>
                        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div
                                class="max-w-md mx-auto space-y-4 lg:max-w-5xl lg:grid lg:grid-cols-2 lg:gap-5 lg:space-y-0">
                                @foreach(\App\Models\Plan::where('price', '<>', 0)->get() as $plan)
                                    <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                                        <div class="px-6 py-8 bg-white sm:p-10 sm:pb-6">
                                            <div>
                                                <h3 class="inline-flex px-4 py-1 rounded-full text-sm font-semibold tracking-wide uppercase bg-gray-100 text-gray-600">
                                                    {{ $plan->name }} Subscription
                                                </h3>
                                            </div>
                                            <div class="mt-4 flex items-baseline text-6xl font-extrabold">
                                                <span class="text-5xl">${{ $plan->price }}</span>
                                                <span class="ml-1 text-2xl font-medium text-gray-500">
                                                    / month
                                                </span>
                                            </div>
                                            <p class="mt-5 text-lg text-gray-500">
                                                @if($plan->name === 'Standard')
                                                    Standard licence
                                                @else
                                                    For users that want to give extra support
                                                @endif
                                            </p>
                                        </div>
                                        <div
                                            class="flex-1 flex flex-col justify-between px-6 pt-6 pb-8 bg-gray-50 space-y-6 sm:p-10 sm:pt-6">
                                            <ul class="space-y-4">
                                                <li class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        <x-icons.o-check class="text-green-500"/>
                                                    </div>
                                                    <p class="ml-3 text-base text-gray-700">
                                                        Monthly Updates
                                                    </p>
                                                </li>

                                                <li class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        <x-icons.o-check class="text-green-500"/>
                                                    </div>
                                                    <p class="ml-3 text-base text-gray-700">
                                                        Cloud Based Configs ({{ $plan->config_limit }} slots)
                                                    </p>
                                                </li>

                                                <li class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        @if($plan->beta_access)
                                                            <x-icons.o-check class="text-green-500"/>
                                                        @else
                                                            <x-icons.o-x class="text-red-700"/>
                                                        @endif
                                                    </div>
                                                    <p class="ml-3 text-base text-gray-700">
                                                        Access to Beta Builds
                                                    </p>
                                                </li>

                                                <li class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        @if($plan->capes_access)
                                                            <x-icons.o-check class="text-green-500"/>
                                                        @else
                                                            <x-icons.o-x class="text-red-700"/>
                                                        @endif
                                                    </div>
                                                    <p class="ml-3 text-base text-gray-700">
                                                        Cape Visibility
                                                    </p>
                                                </li>

                                                <li class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        @if($plan->capes_access)
                                                            <x-icons.o-check class="text-green-500"/>
                                                        @else
                                                            <x-icons.o-x class="text-red-700"/>
                                                        @endif
                                                    </div>
                                                    <p class="ml-3 text-base text-gray-700">
                                                        Access to modify your cape
                                                    </p>
                                                </li>
                                            </ul>
                                            <div class="rounded-md shadow">
                                                <a href="{{ route('home.subscription') }}"
                                                   class="flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                    Subscribe
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- FAQ Section --}}
            <section class="bg-gray-50" id="faq">
                <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                        <div>
                            <h2 class="text-3xl font-extrabold text-gray-900">
                                Frequently asked questions
                            </h2>
                            <p class="mt-4 text-lg text-gray-500">
                                Can’t find the answer you’re looking for? <br> Join
                                our Discord and open a support ticket.
                            </p>
                            <a type="button"
                               href="{{ config('services.discord.invite') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 mt-2">
                                <span class="pr-2">
                                    <svg width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M6.552 6.712c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888.008-.488-.36-.888-.816-.888zm2.92 0c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888s-.36-.888-.816-.888z"/>
                                        <path
                                            d="M13.36 0H2.64C1.736 0 1 .736 1 1.648v10.816c0 .912.736 1.648 1.64 1.648h9.072l-.424-1.48 1.024.952.968.896L15 16V1.648C15 .736 14.264 0 13.36 0zm-3.088 10.448s-.288-.344-.528-.648c1.048-.296 1.448-.952 1.448-.952-.328.216-.64.368-.92.472-.4.168-.784.28-1.16.344a5.604 5.604 0 0 1-2.072-.008 6.716 6.716 0 0 1-1.176-.344 4.688 4.688 0 0 1-.584-.272c-.024-.016-.048-.024-.072-.04-.016-.008-.024-.016-.032-.024-.144-.08-.224-.136-.224-.136s.384.64 1.4.944c-.24.304-.536.664-.536.664-1.768-.056-2.44-1.216-2.44-1.216 0-2.576 1.152-4.664 1.152-4.664 1.152-.864 2.248-.84 2.248-.84l.08.096c-1.44.416-2.104 1.048-2.104 1.048s.176-.096.472-.232c.856-.376 1.536-.48 1.816-.504.048-.008.088-.016.136-.016a6.521 6.521 0 0 1 4.024.752s-.632-.6-1.992-1.016l.112-.128s1.096-.024 2.248.84c0 0 1.152 2.088 1.152 4.664 0 0-.68 1.16-2.448 1.216z"/>
                                    </svg>
                                </span>
                                Join Discord
                            </a>
                        </div>
                        <div class="mt-12 lg:mt-0 lg:col-span-2">
                            <dl class="space-y-12">
                                {{--<div>
                                    <dt class="text-lg leading-6 font-medium text-gray-900">
                                        No Java detected
                                    </dt>
                                    <dd class="mt-2 text-base text-gray-500">
                                        If the launcher has failed to detect Java on your system, delete the java from
                                        <br>
                                        your system if you have it installed. Next open Chrome browser and navigate to
                                        <a class="text-blue-500" href="https://java.com/en/">Java</a> <br>
                                        Download the java and install it. Issue should be resolved <br>
                                        <p class="text-gray-800">(Dont download java through Edge browser it will not
                                            work)</p>
                                    </dd>
                                </div>--}}
                                <div>
                                    <dt class="text-lg leading-6 font-medium text-gray-900">
                                        My game is crashing on startup
                                    </dt>
                                    <dd class="mt-2 text-base text-gray-500">
                                        Open start menu and type <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">%APPDATA%</span>,
                                        once you are in the roaming folder open the <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">.envy</span>
                                        folder. <br>
                                        Next go to the <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">minecraft</span>
                                        folder, once you are in there delete the <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">Envy</span>
                                        folder and launch<br>
                                        the client again.
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Team Section --}}
            <section class="bg-gray-75" id="team">
                <div class="mx-auto py-12 px-4 max-w-7xl sm:px-6 lg:px-8 lg:py-24">
                    <div class="grid grid-cols-1 gap-12 lg:grid-cols-3 lg:gap-8">
                        <div class="space-y-5 sm:space-y-4">
                            <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Meet the team</h2>
                            <p class="text-xl text-gray-500">People that keep this thing up and going.</p>
                        </div>
                        <div class="lg:col-span-2">
                            <ul class="space-y-12 sm:grid sm:grid-cols-2 sm:gap-12 sm:space-y-0 lg:gap-x-8">
                                <li>
                                    <div class="flex items-center space-x-4 lg:space-x-6">
                                        <img class="w-16 h-16 rounded-full lg:w-20 lg:h-20"
                                             src="https://avatar.tobi.sh/avatar/7a269b15dc630d0bfcc7a4922c69eedc.svg?text=HAQ"
                                             alt="haq proile picture">
                                        <div class="font-medium text-lg leading-6 space-y-1">
                                            <h3>Haq</h3>
                                            <p class="text-gray-500">Founder</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center space-x-4 lg:space-x-6">
                                        <img class="w-16 h-16 rounded-full lg:w-20 lg:h-20"
                                             src="https://avatar.tobi.sh/avatar/7f7365d0acacde6fe7fbb24abd1d0b0e.svg?text=MAT"
                                             alt="mat profile picture">
                                        <div class="font-medium text-lg leading-6 space-y-1">
                                            <h3>Mat</h3>
                                            <p class="text-gray-500">Developer</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {{--Footer Section--}}
            <footer class="bg-gray-800">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
                    <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                        <div class="grid grid-cols-2 gap-8 xl:col-span-2">
                            <div class="md:grid md:grid-cols-2 md:gap-8">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
                                        Pages
                                    </h3>
                                    <ul class="mt-4 space-y-4">
                                        <li>
                                            <a href="#home" class="text-base text-gray-300 hover:text-white">
                                                Home
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#features" class="text-base text-gray-300 hover:text-white">
                                                Features
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#pricing" class="text-base text-gray-300 hover:text-white">
                                                Pricing
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#team" class="text-base text-gray-300 hover:text-white">
                                                Team
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-12 md:mt-0">
                                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
                                        Support
                                    </h3>
                                    <ul class="mt-4 space-y-4">
                                        <li>
                                            <a href="mailto:contact@envyclient.com"
                                               class="text-base text-gray-300 hover:text-white">
                                                Email
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ config('services.discord.invite') }}"
                                               class="text-base text-gray-300 hover:text-white">
                                                Discord
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="md:grid md:grid-cols-2 md:gap-8">
                                <div class="mt-12 md:mt-0">
                                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
                                        Legal
                                    </h3>
                                    <ul class="mt-4 space-y-4">
                                        <li>
                                            <a href="{{ route('terms') }}"
                                               class="text-base text-gray-300 hover:text-white">
                                                Terms
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 border-t border-gray-700 pt-8 md:flex md:items-center md:justify-between">
                        <div class="flex space-x-6 md:order-2">
                            <a href="https://github.com/envyclient" class="text-gray-400 hover:text-gray-300">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
                                </svg>
                            </a>
                            <a href="mailto:contact@envyclient.com" class="text-gray-400 hover:text-gray-300">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                                </svg>
                            </a>
                            <a href="{{ config('services.discord.invite') }}" class="text-gray-400 hover:text-gray-300">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M6.552 6.712c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888.008-.488-.36-.888-.816-.888zm2.92 0c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888s-.36-.888-.816-.888z"/>
                                    <path
                                        d="M13.36 0H2.64C1.736 0 1 .736 1 1.648v10.816c0 .912.736 1.648 1.64 1.648h9.072l-.424-1.48 1.024.952.968.896L15 16V1.648C15 .736 14.264 0 13.36 0zm-3.088 10.448s-.288-.344-.528-.648c1.048-.296 1.448-.952 1.448-.952-.328.216-.64.368-.92.472-.4.168-.784.28-1.16.344a5.604 5.604 0 0 1-2.072-.008 6.716 6.716 0 0 1-1.176-.344 4.688 4.688 0 0 1-.584-.272c-.024-.016-.048-.024-.072-.04-.016-.008-.024-.016-.032-.024-.144-.08-.224-.136-.224-.136s.384.64 1.4.944c-.24.304-.536.664-.536.664-1.768-.056-2.44-1.216-2.44-1.216 0-2.576 1.152-4.664 1.152-4.664 1.152-.864 2.248-.84 2.248-.84l.08.096c-1.44.416-2.104 1.048-2.104 1.048s.176-.096.472-.232c.856-.376 1.536-.48 1.816-.504.048-.008.088-.016.136-.016a6.521 6.521 0 0 1 4.024.752s-.632-.6-1.992-1.016l.112-.128s1.096-.024 2.248.84c0 0 1.152 2.088 1.152 4.664 0 0-.68 1.16-2.448 1.216z"/>
                                </svg>
                            </a>
                        </div>
                        <p class="mt-8 text-base text-gray-400 md:mt-0 md:order-1">
                            &copy; 2022 {{ config('app.name') }}
                        </p>
                    </div>
                </div>
            </footer>

        </main>

    </div>
@endsection
