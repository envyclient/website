@extends('layouts.dash')

@section('title', 'Referrals')

@section('content')

    <section>
        @livewire('admin.referral.list-referral-codes')
    </section>

    <section class="mt-4">
        @livewire('admin.referral.create-referral-code')
    </section>
@endsection
