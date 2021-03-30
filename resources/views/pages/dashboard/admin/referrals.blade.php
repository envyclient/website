@extends('layouts.dash')

@section('title', 'Referrals')

@section('content')
    <div>
        <div class="alert alert-dark" style="font-size:25px;">
            Referral Codes
        </div>

        @livewire('referral.list-referral-codes')
    </div>

    <div>
        <div class="alert alert-dark" style="font-size:25px;">
            Create Referral Code
        </div>

        @livewire('referral.create-referral-code')
    </div>
@endsection
