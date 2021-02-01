@extends('layouts.dash')

@section('title', 'Discord')

@section('content')

    <div style="width:98%;margin:0 auto">

        <div class="row">
            <div class="col">
                <div class="alert alert-dark" style="font-size:25px;">
                    <svg class="mb-1" style="width:32px;height:32px;" viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M16 17V19H2V17S2 13 9 13 16 17 16 17M12.5 7.5A3.5 3.5 0 1 0 9 11A3.5 3.5 0 0 0 12.5 7.5M15.94 13A5.32 5.32 0 0 1 18 17V19H22V17S22 13.37 15.94 13M15 4A3.39 3.39 0 0 0 13.07 4.59A5 5 0 0 1 13.07 10.41A3.39 3.39 0 0 0 15 11A3.5 3.5 0 0 0 15 4Z"/>
                    </svg>
                    Server
                </div>

                <iframe src="https://discord.com/widget?id=794374279395147777&theme=dark"
                        width="350"
                        height="500"
                        allowtransparency="true"
                        style="border:0;"
                        sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts">
                </iframe>
            </div>
            <div class="col">
                <div class="alert alert-dark" style="font-size:25px;">
                    <svg class="mb-1" style="width:32px;height:32px;" viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                    </svg>
                    Account
                </div>

                <div class="card">
                    <div class="card-body">
                        @if($user->discord_id === null)
                            <div class="d-grid gap-2">
                                <a class="btn btn-primary btn-lg" href="{{ route('connect.discord') }}">
                                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                              d="M22,24L16.75,19L17.38,21H4.5A2.5,2.5 0 0,1 2,18.5V3.5A2.5,2.5 0 0,1 4.5,1H19.5A2.5,2.5 0 0,1 22,3.5V24M12,6.8C9.32,6.8 7.44,7.95 7.44,7.95C8.47,7.03 10.27,6.5 10.27,6.5L10.1,6.33C8.41,6.36 6.88,7.53 6.88,7.53C5.16,11.12 5.27,14.22 5.27,14.22C6.67,16.03 8.75,15.9 8.75,15.9L9.46,15C8.21,14.73 7.42,13.62 7.42,13.62C7.42,13.62 9.3,14.9 12,14.9C14.7,14.9 16.58,13.62 16.58,13.62C16.58,13.62 15.79,14.73 14.54,15L15.25,15.9C15.25,15.9 17.33,16.03 18.73,14.22C18.73,14.22 18.84,11.12 17.12,7.53C17.12,7.53 15.59,6.36 13.9,6.33L13.73,6.5C13.73,6.5 15.53,7.03 16.56,7.95C16.56,7.95 14.68,6.8 12,6.8M9.93,10.59C10.58,10.59 11.11,11.16 11.1,11.86C11.1,12.55 10.58,13.13 9.93,13.13C9.29,13.13 8.77,12.55 8.77,11.86C8.77,11.16 9.28,10.59 9.93,10.59M14.1,10.59C14.75,10.59 15.27,11.16 15.27,11.86C15.27,12.55 14.75,13.13 14.1,13.13C13.46,13.13 12.94,12.55 12.94,11.86C12.94,11.16 13.45,10.59 14.1,10.59Z"/>
                                    </svg>
                                    Connect Account
                                </a>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label" for="discord-id">Discord ID</label>
                                <input id="discord-id"
                                       class="form-control"
                                       value="{{ $user->discord_id }}"
                                       disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="discord-name">Discord Name</label>
                                <input id="discord-name"
                                       class="form-control"
                                       value="{{ $user->discord_name}}"
                                       disabled>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection
