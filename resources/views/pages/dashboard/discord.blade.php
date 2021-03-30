@extends('layouts.dash')

@section('title', 'Discord')

@section('content')
    <div class="row">
        <div class="col-md">
            <div class="alert alert-dark fs-4">
                <svg class="pb-1" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M2 2a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h1v2H2a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1a2 2 0 0 0-2-2h-1V7h1a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2zm.5 3a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm2 0a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm-2 7a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm2 0a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zM12 7v2H4V7h8z"/>
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
        <div class="col-md">
            <div class="alert alert-dark fs-4">
                <svg class="pb-1" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm-1 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm-3 4c2.623 0 4.146.826 5 1.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-1.245C3.854 11.825 5.377 11 8 11z"/>
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
@endsection
