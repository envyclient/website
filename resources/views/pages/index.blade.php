@extends('layouts.guest')

@section('body')
    <div class="min-h-screen bg-white">
        <header>
            <div class="relative bg-white" x-data="{ open: false }">
                <div
                    class="flex justify-between items-center max-w-7xl mx-auto px-4 py-6 sm:px-6 md:justify-start md:space-x-10 lg:px-8">
                    <div class="flex justify-start lg:w-0 lg:flex-1">
                        <img class="h-8 w-auto sm:h-12" src="{{asset('logo.svg')}}" alt="envy logo">
                    </div>
                    <div class="-mr-2 -my-2 md:hidden">
                        <button
                            type="button"
                            @click="open = true"
                            class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gray-500"
                        >
                            <!-- Heroicon name: outline/menu -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" aria-hidden="true">
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
                        <a href="{{ route('login') }}"
                           class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900">
                            Sign in
                        </a>
                        <a href="{{ route('register') }}"
                           class="ml-8 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700">
                            Sign up
                        </a>
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
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
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
                                <a href="{{ route('register') }}"
                                   class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700">
                                    Sign up
                                </a>
                                <p class="mt-6 text-center text-base font-medium text-gray-500">
                                    Existing customer?
                                    <a href="{{ route('login') }}" class="text-gray-900">
                                        Sign in
                                    </a>
                                </p>
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
                                 alt="People working on laptops">
                            <div class="absolute inset-0 bg-green-700" style="mix-blend-mode: multiply;"></div>
                        </div>
                        <div class="relative px-4 py-16 sm:px-6 sm:py-24 lg:py-32 lg:px-8">
                            <h1 class="text-center text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                                <span class="block text-white">Envy</span>
                            </h1>
                            <p class="mt-6 max-w-lg mx-auto text-center text-xl text-gray-200 sm:max-w-3xl">
                                Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat
                                commodo. Elit sunt amet fugiat veniam occaecat fugiat aliqua.
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
                                    <a href="{{ config('discord.invite') }}"
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
                                            Envy offers a modern and sleek looking account manager with
                                            built in multiplayer screen allowing you to connect to a wanted
                                            server instantly
                                        </p>
                                        <br>
                                        <hr>
                                        <p class="mt-2 text-lg text-gray-600">
                                            The account manager also has a built in <a
                                                href="https://thealtening.com/" class="text-blue-500">The
                                                Altening</a> support
                                        </p>
                                        <div class="mt-6">
                                            <a href="https://thealtening.com/"
                                               class="inline-flex px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                                The Altening
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-12 sm:mt-16 lg:mt-0">
                                <div class="pl-4 -mr-48 sm:pl-6 md:-mr-16 lg:px-0 lg:m-0 lg:relative lg:h-full">
                                    <img
                                        class="w-full rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 lg:absolute lg:left-0 lg:h-full lg:w-auto lg:max-w-none"
                                        src="{{ asset('assets/features/account_manager.png') }}"
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
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                       viewBox="0 0 24 24" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round"
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
                                            ClickGui for the client is built on the idea of it being
                                            easy to use, it is the old school style of click guis but with a modern
                                            twist that includes a web config window and a console window for all the
                                            commands
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
                            <div class="mt-12 sm:mt-16 lg:mt-0 lg:col-start-1">
                                <div class="pr-4 -ml-48 sm:pr-6 md:-ml-16 lg:px-0 lg:m-0 lg:relative lg:h-full">
                                    <img
                                        class="w-full rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 lg:absolute lg:right-0 lg:h-full lg:w-auto lg:max-w-none"
                                        src="{{ asset('assets/features/client.png') }}"
                                        alt="envy client ui">
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
                                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                                    <div class="px-6 py-8 bg-white sm:p-10 sm:pb-6">
                                        <div>
                                            <h3 class="inline-flex px-4 py-1 rounded-full text-sm font-semibold tracking-wide uppercase bg-gray-100 text-gray-600"
                                                id="tier-standard">
                                                Standard Subscription
                                            </h3>
                                        </div>
                                        <div class="mt-4 flex items-baseline text-6xl font-extrabold">
                                            <span class="text-5xl">$7</span>
                                            <span class="ml-1 text-2xl font-medium text-gray-500">
                                                    / month
                                                </span>
                                        </div>
                                        <p class="mt-5 text-lg text-gray-500">
                                            Standard licence
                                        </p>
                                    </div>
                                    <div
                                        class="flex-1 flex flex-col justify-between px-6 pt-6 pb-8 bg-gray-50 space-y-6 sm:p-10 sm:pt-6">
                                        <ul class="space-y-4">
                                            <li class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <!-- Heroicon name: outline/check -->
                                                    <svg class="h-6 w-6 text-green-500"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                         aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <p class="ml-3 text-base text-gray-700">
                                                    Monthly Updates
                                                </p>
                                            </li>

                                            <li class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <!-- Heroicon name: outline/check -->
                                                    <svg class="h-6 w-6 text-green-500"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                         aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <p class="ml-3 text-base text-gray-700">
                                                    Cloud based configs (5 slots)
                                                </p>
                                            </li>

                                            <li class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-700"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </div>
                                                <p class="ml-3 text-base text-gray-700">
                                                    Access to Beta builds
                                                </p>
                                            </li>

                                            <li class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-700"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </div>
                                                <p class="ml-3 text-base text-gray-700">
                                                    Cape visibility
                                                </p>
                                            </li>

                                            <li class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-700"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
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

                                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                                    <div class="px-6 py-8 bg-white sm:p-10 sm:pb-6">
                                        <div>
                                            <h3 class="inline-flex px-4 py-1 rounded-full text-sm font-semibold tracking-wide uppercase bg-gray-100 text-gray-600"
                                                id="tier-standard">
                                                Premium Subscription
                                            </h3>
                                        </div>
                                        <div class="mt-4 flex items-baseline text-6xl font-extrabold">
                                            <span class="text-5xl">$10</span>
                                            <span class="ml-1 text-2xl font-medium text-gray-500">
                                                    / month
                                                </span>
                                        </div>
                                        <p class="mt-5 text-lg text-gray-500">
                                            For users that want to give extra support
                                        </p>
                                    </div>
                                    <div
                                        class="flex-1 flex flex-col justify-between px-6 pt-6 pb-8 bg-gray-50 space-y-6 sm:p-10 sm:pt-6">
                                        <ul class="space-y-4">
                                            <li class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <!-- Heroicon name: outline/check -->
                                                    <svg class="h-6 w-6 text-green-500"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                         aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <p class="ml-3 text-base text-gray-700">
                                                    Monthly Updates
                                                </p>
                                            </li>

                                            <li class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <!-- Heroicon name: outline/check -->
                                                    <svg class="h-6 w-6 text-green-500"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                         aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <p class="ml-3 text-base text-gray-700">
                                                    Cloud based configs (15 slots)
                                                </p>
                                            </li>

                                            <li class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <!-- Heroicon name: outline/check -->
                                                    <svg class="h-6 w-6 text-green-500"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                         aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <p class="ml-3 text-base text-gray-700">
                                                    Access to Beta builds
                                                </p>
                                            </li>

                                            <li class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <!-- Heroicon name: outline/check -->
                                                    <svg class="h-6 w-6 text-green-500"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                         aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <p class="ml-3 text-base text-gray-700">
                                                    Cape visibility
                                                </p>
                                            </li>

                                            <li class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <!-- Heroicon name: outline/check -->
                                                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M5 13l4 4L19 7"/>
                                                    </svg>
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
                               href="{{ config('discord.invite') }}"
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
                                <div>
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
                                </div>
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

                                <!-- More items... -->
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
                                             src="{{ asset('assets/team/haq.jfif') }}" alt="">
                                        <div class="font-medium text-lg leading-6 space-y-1">
                                            <h3>Haq</h3>
                                            <p class="text-gray-500">Founder / CEO</p>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="flex items-center space-x-4 lg:space-x-6">
                                        <img class="w-16 h-16 rounded-full lg:w-20 lg:h-20"
                                             src="{{ asset('assets/team/mat.png') }}" alt="">
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
                                            <a href="{{ config('discord.invite') }}"
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
                                <span class="sr-only">GitHub</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="mailto:contact@envyclient.com" class="text-gray-400 hover:text-gray-300">
                                <span class="sr-only">Mail</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </a>
                            <a href="{{ config('discord.invite') }}" class="text-gray-400 hover:text-gray-300">
                                <span class="sr-only">Discord</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M6.552 6.712c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888.008-.488-.36-.888-.816-.888zm2.92 0c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888s-.36-.888-.816-.888z"/>
                                    <path
                                        d="M13.36 0H2.64C1.736 0 1 .736 1 1.648v10.816c0 .912.736 1.648 1.64 1.648h9.072l-.424-1.48 1.024.952.968.896L15 16V1.648C15 .736 14.264 0 13.36 0zm-3.088 10.448s-.288-.344-.528-.648c1.048-.296 1.448-.952 1.448-.952-.328.216-.64.368-.92.472-.4.168-.784.28-1.16.344a5.604 5.604 0 0 1-2.072-.008 6.716 6.716 0 0 1-1.176-.344 4.688 4.688 0 0 1-.584-.272c-.024-.016-.048-.024-.072-.04-.016-.008-.024-.016-.032-.024-.144-.08-.224-.136-.224-.136s.384.64 1.4.944c-.24.304-.536.664-.536.664-1.768-.056-2.44-1.216-2.44-1.216 0-2.576 1.152-4.664 1.152-4.664 1.152-.864 2.248-.84 2.248-.84l.08.096c-1.44.416-2.104 1.048-2.104 1.048s.176-.096.472-.232c.856-.376 1.536-.48 1.816-.504.048-.008.088-.016.136-.016a6.521 6.521 0 0 1 4.024.752s-.632-.6-1.992-1.016l.112-.128s1.096-.024 2.248.84c0 0 1.152 2.088 1.152 4.664 0 0-.68 1.16-2.448 1.216z"/>
                                </svg>
                            </a>
                        </div>
                        <p class="mt-8 text-base text-gray-400 md:mt-0 md:order-1">
                            &copy; 2021 Envy Client. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>

        </main>

    </div>
@endsection
