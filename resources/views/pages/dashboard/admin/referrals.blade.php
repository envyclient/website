@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">

        <div>
            <div class="alert alert-primary" style="font-size:25px;">
                Referral Codes
            </div>

            @livewire('referral.list-referral-codes')
        </div>

        <div>
            <div class="alert alert-secondary" style="font-size:25px;">
                Create Referral Code
            </div>

            @livewire('referral.create-referral-code')
        </div>

    </div>
@endsection
