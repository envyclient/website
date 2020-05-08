@extends('layouts.dash')

@section("content")
    <div class="tab-content" style="width:95%;margin:0 auto">
        <div class="tab-pane fade show active" id="statistics" role="tabpanel">
            stats
        </div>

        <div class="tab-pane fade custom-panel" id="users" role="tabpanel">

            <!-- total users -->
            <div>
                <div class="alert alert-primary" style="font-size:25px;">
                    Total Users
                </div>
                <user-stats url="{{ route('api.admin.users.stats') }}"
                            type="total"
                            api-token="{{ $user->api_token }}">
                </user-stats>
            </div>

            <br>

            <!-- premium users -->
            <div>
                <div class="alert alert-success" style="font-size:25px;">
                    Premium Users
                </div>
                <user-stats url="{{ route('api.admin.users.stats') }}"
                            type="premium"
                            api-token="{{ $user->api_token }}">
                </user-stats>
            </div>

            <br>

            <!-- users table -->
            <div>
                <div class="alert alert-secondary" style="font-size:25px;">
                    User Management
                </div>
                <users-table url="{{ route('api.admin.users')  }}"
                             credits-url="{{ route('api.admin.users.credits') }}"
                             ban-url="{{ route('api.admin.users.ban') }}"
                             un-ban-url="{{ route('api.admin.users.unban') }}"
                             api-token="{{ $user->api_token }}">
                </users-table>
            </div>
        </div>
    </div>
@endsection
