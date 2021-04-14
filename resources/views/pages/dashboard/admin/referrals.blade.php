@extends('layouts.dash')

@section('title', 'Referrals')

@section('content')

    @livewire('admin.referral.list-referral-codes')

    @livewire('admin.referral.create-referral-code')
@endsection
