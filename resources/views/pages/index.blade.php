@extends('layouts.dash')

@section('content')
    <div class="tab-content" style="width:95%;margin:0 auto">

        <!-- settings -->
        @include('pages.tabs.profile')
        @include('pages.tabs.security')

        <!-- billing -->
        @include('pages.tabs.subscriptions')
       {{-- @include('pages.tabs.credits')
        @include('pages.tabs.transactions')--}}

        <!-- disable account modal -->
        <div class="modal fade" id="disableAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Wooooow! Are you sure there bud?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                        </button>
                    </div>
                    <div class="modal-body" style="color:red;">
                        By disabling your account you will lose access to your account and if you have a subscription it
                        will continue until the expire date.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        {!! Form::open(['action' => 'UsersController@disable', 'method' => 'DELETE']) !!}
                        {{ Form::submit('Disable Account', ['class' => 'btn btn-outline-danger m-sm-2']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- TODO: use js to set dynamic data -->
        @foreach($plans as $plan)
            <div class="modal fade" id="{{ $plan->name }}-modal" tabindex="-1" role="dialog"
                 aria-labelledby="{{ $plan->name }}-label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="{{ $plan->name }}-label">Features - {{ $plan->name }} Plan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>{{ $plan->name }}</p>
                            <p>{{ $plan->config_limit }} Configs</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach

@endsection
