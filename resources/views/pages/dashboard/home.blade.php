@extends('layouts.dash')

@section('title', 'Home')

@section('content')

    {{--Discord Account Connect--}}
    @if($user->discord_id === null)
        <x-card title="Connect Discord Account"
                subtitle="Connect your discord account to enable role syncing with the server."
                class="mb-4">
            <a href="{{ route('connect.discord') }}">
                <x-button.primary>Connect</x-button.primary>
            </a>
        </x-card>
    @endif

    {{--Referral Code--}}
    @if($user->referral_code_id === null)
        <x-card title="Use referral code" subtitle="If you were referred by another user please enter their code.">
            <form class="sm:flex sm:items-center" action="{{ route('users.referral-code') }}" method="post">
                @csrf
                <x-input.text
                    id="referral-code"
                    name="referral-code"
                    placeholder="code"
                    value="{{ old('referral-code') }}"
                    required
                />
                <x-button.primary class="ml-3" type="submit">Use</x-button.primary>
            </form>
        </x-card>
    @endif

    {{--Upload Cape--}}
    @if($user->hasCapesAccess())
        @livewire('user.upload-cape')
    @endif

    {{--Media Applications--}}
    @livewire('user.media-requests')

    {{--Download Launcher Button--}}
    @if($user->subscription !== null)
        <a type="button"
           href="{{ route('launcher.download') }}"
           class="mt-4 w-full px-6 py-3 text-center border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Download Launcher
        </a>
    @endif

@endsection
