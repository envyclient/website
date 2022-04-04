@extends('layouts.base')

@section('body')
    <div class="h-screen flex overflow-hidden bg-gray-100" x-data="{ open: false }">

        {{-- Mobile Sidebar --}}
        <div class="fixed inset-0 flex z-40 md:hidden" x-show="open">
            <div
                class="fixed inset-0 bg-gray-600 bg-opacity-75"
                x-show="open"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>

            <div
                class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-800"
                x-show="open"
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
            >
                <div
                    class="absolute top-0 right-0 -mr-12 pt-2"
                    x-show="open"
                    x-transition:enter="ease-in-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in-out duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                >
                    <button
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <!-- Heroicon name: outline/x -->
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             @click="open = false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-shrink-0 flex items-center px-4">
                        {{-- Envy Logo --}}
                        <div class="flex items-center flex-shrink-0">
                            <a href="/" class="flex items-center text-white">
                                <svg width="48" height="48" viewBox="0 0 29 41" class="me-2 ms-2"
                                     style="fill: rgb(33, 170, 47); stroke: rgb(244, 250, 246); stroke-width: 0.125pt;">
                                    <path fill="#21aa2f" stroke="#f4faf6"
                                          style="fill-opacity: 0.54; stroke-opacity: 0.54;"
                                          d="M2.5 38.938c-.24 0-.438-.195-.438-.436V3.62c0-.24.196-.436.438-.436h24.38L22.344 9.59c-.087.123-.233.196-.39.196h-9.902c-.326 0-.59.25-.59.56V17.2c0 .308.264.56.59.56h8.466c.257 0 .466.196.466.436v5.73c0 .24-.21.436-.467.436h-8.467c-.326 0-.59.252-.59.562v6.852c0 .31.264.56.59.56h9.902c.156 0 .302.074.39.197l4.534 6.406H2.5z"></path>
                                    <path fill="#21aa2f" stroke="#f4faf6" style="stroke-width: 0.125pt;"
                                          d="M2.5 37.816c-.24 0-.438-.195-.438-.436V2.5c0-.24.196-.437.438-.437h24.38L22.344 8.47c-.087.122-.232.195-.39.195h-9.902c-.326 0-.59.25-.59.56v6.853c0 .31.264.56.59.56h8.466c.257 0 .466.197.466.437v5.73c0 .24-.21.436-.467.436h-8.467c-.326 0-.59.253-.59.563v6.852c0 .31.264.56.59.56h9.902c.157 0 .303.074.39.196l4.534 6.408H2.5z"></path>
                                </svg>
                                <span class="text-2xl font-semibold ml-1">Envy Client</span>
                            </a>
                        </div>
                    </div>

                    {{-- Sidebar Content --}}
                    <nav class="mt-5 px-2 space-y-1">
                        @include('inc.sidebar')
                    </nav>
                </div>

                {{-- User Profile --}}
                <div class="flex-shrink-0 flex bg-gray-700 p-4">
                    @livewire('show-profile-image')
                </div>
            </div>

            <div class="flex-shrink-0 w-14">
                <!-- Force sidebar to shrink to fit close icon -->
            </div>
        </div>

        {{-- Desktop Sidebar --}}
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64">
                <div class="flex flex-col h-0 flex-1 bg-gray-800">
                    <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">

                        {{-- Envy Logo --}}
                        <div class="flex items-center flex-shrink-0 px-4">
                            <a href="/" class="flex items-center text-white">
                                <svg width="48" height="48" viewBox="0 0 29 41" class="me-2 ms-2"
                                     style="fill: rgb(33, 170, 47); stroke: rgb(244, 250, 246); stroke-width: 0.125pt;">
                                    <path fill="#21aa2f" stroke="#f4faf6"
                                          style="fill-opacity: 0.54; stroke-opacity: 0.54;"
                                          d="M2.5 38.938c-.24 0-.438-.195-.438-.436V3.62c0-.24.196-.436.438-.436h24.38L22.344 9.59c-.087.123-.233.196-.39.196h-9.902c-.326 0-.59.25-.59.56V17.2c0 .308.264.56.59.56h8.466c.257 0 .466.196.466.436v5.73c0 .24-.21.436-.467.436h-8.467c-.326 0-.59.252-.59.562v6.852c0 .31.264.56.59.56h9.902c.156 0 .302.074.39.197l4.534 6.406H2.5z"></path>
                                    <path fill="#21aa2f" stroke="#f4faf6" style="stroke-width: 0.125pt;"
                                          d="M2.5 37.816c-.24 0-.438-.195-.438-.436V2.5c0-.24.196-.437.438-.437h24.38L22.344 8.47c-.087.122-.232.195-.39.195h-9.902c-.326 0-.59.25-.59.56v6.853c0 .31.264.56.59.56h8.466c.257 0 .466.197.466.437v5.73c0 .24-.21.436-.467.436h-8.467c-.326 0-.59.253-.59.563v6.852c0 .31.264.56.59.56h9.902c.157 0 .303.074.39.196l4.534 6.408H2.5z"></path>
                                </svg>
                                <span class="text-2xl font-semibold ml-1">Envy Client</span>
                            </a>
                        </div>

                        {{-- Sidebar Content --}}
                        <nav class="mt-5 flex-1 px-2 bg-gray-800 space-y-1">
                            @include('inc.sidebar')
                        </nav>
                    </div>

                    {{-- User Profile --}}
                    <div class="flex-shrink-0 flex bg-gray-700 p-4">
                        @livewire('show-profile-image')
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3">
                <button
                    class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open sidebar</span>
                    <!-- Heroicon name: outline/menu -->
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" @click="open = true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
            <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none" tabindex="0">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <h1 class="text-2xl font-semibold text-gray-900">
                            @hasSection('title')
                                @yield('title')
                            @else
                                Dashboard
                            @endif
                        </h1>
                    </div>
                    <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 md:px-8">

                        <section class="mb-3">
                            @include('inc.notifications')
                        </section>

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <x-support-bubble/>

    @yield('js')
@endsection
