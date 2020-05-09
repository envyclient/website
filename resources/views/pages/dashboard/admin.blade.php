@extends('layouts.dash')

@section("content")
    <div class="tab-content" style="width:95%;margin:0 auto">

        <!-- transactions -->
        <div class="tab-pane fade custom-panel show active" id="statistics" role="tabpanel">

            <!-- transaction stats -->
            <div id="transaction-stats">
                <div class="alert alert-primary" style="font-size:25px;">
                    Stats
                </div>
                <transaction-stats url="{{ route('api.admin.transactions.stats') }}"
                                   api-token="{{ $apiToken }}">
                </transaction-stats>
            </div>

            <br>

            <!-- transactions table -->
            <div id="transactions-table">
                <div class="alert alert-secondary" style="font-size:25px;">
                    Transactions
                </div>
                <transactions-table url="{{ route('api.admin.transactions') }}"
                                    api-token="{{ $apiToken }}">
                </transactions-table>
            </div>
        </div>

        <!-- users -->
        <div class="tab-pane fade custom-panel" id="users" role="tabpanel">
            <div class="row">
                <!-- total users -->
                <div class="col">
                    <div class="alert alert-primary" style="font-size:25px;">
                        Total Users
                    </div>
                    <user-stats url="{{ route('api.admin.users.stats') }}"
                                type="total"
                                api-token="{{ $apiToken }}">
                    </user-stats>
                </div>

                <br>

                <!-- premium users -->
                <div class="col">
                    <div class="alert alert-success" style="font-size:25px;">
                        Premium Users
                    </div>
                    <user-stats url="{{ route('api.admin.users.stats') }}"
                                type="premium"
                                api-token="{{ $apiToken }}">
                    </user-stats>
                </div>
            </div>

            <br>

            <!-- users table -->
            <div id="users-table">
                <div class="alert alert-secondary" style="font-size:25px;">
                    User Management
                </div>
                <users-table url="{{ route('api.admin.users')  }}"
                             credits-url="{{ route('api.admin.users.credits') }}"
                             ban-url="{{ route('api.admin.users.ban') }}"
                             un-ban-url="{{ route('api.admin.users.unban') }}"
                             api-token="{{ $apiToken }}">
                </users-table>
            </div>
        </div>
    </div>
@endsection
