@extends('layouts.dash')

@section('title', 'Referrals')

@section('content')
    <div>
        <div class="alert alert-dark fs-4">
            Referral Codes
        </div>

        @livewire('admin.referral.list-referral-codes')
    </div>

    <div>
        <div class="alert alert-dark fs-4">
            Create Referral Code
        </div>

        @livewire('admin.referral.create-referral-code')
    </div>
@endsection
