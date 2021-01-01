@extends('layouts.dash')

@section('content')

    <div style="width:98%;margin:0 auto">

        <div class="row">
            <div class="col">
                <div class="alert alert-secondary" style="font-size:25px;">
                    <i class="fas fa-user-friends" style="padding-right:10px;"></i>
                    Server
                </div>

                <iframe src="https://discord.com/widget?id=794374279395147777&theme=dark"
                        width="350"
                        height="500"
                        allowtransparency="true"
                        frameborder="0"
                        sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts">
                </iframe>
            </div>
            <div class="col">
                <div class="alert alert-secondary" style="font-size:25px;">
                    <i class="fas fa-user" style="padding-right:10px;"></i>
                    Account
                </div>

                @if($user->discord_id === null)
                    <a class="btn btn-primary" href="{{ route('oauth.discord.login') }}">
                        Connect Account
                    </a>
                @else
                    <div class="mb-3">
                        <label class="form-label" for="discord-id">Discord ID</label>
                        <input id="discord-id"
                               class="form-control"
                               value="{{ $user->discord_id }}"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="discord-name">Discord Name</label>
                        <input id="discord-name"
                               class="form-control"
                               value="{{ $user->discord_name}}"
                               readonly>
                    </div>
                @endif
            </div>
        </div>

    </div>

@endsection
