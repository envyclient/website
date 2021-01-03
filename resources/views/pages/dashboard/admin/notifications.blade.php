@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">
        <div>
            <div class="alert alert-dark" style="font-size:25px;">
                Notifications
            </div>

            @livewire('notification.list-notifications')
        </div>

        <div>
            <div class="alert alert-dark" style="font-size:25px;">
                Create Client Notification
            </div>

            @livewire('notification.create-notification')
        </div>
    </div>
@endsection
