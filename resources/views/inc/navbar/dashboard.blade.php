<div class="sidebar-menu d-inline-block">
    <div class="list-group">
        <a class="navbar-brand text-white" href="{{ route('home') }}">
            <div class="container text-center">
                <img src="{{ asset('/assets/logo_512x512.png') }}"
                     style="width:128px;height:128px;margin-top:10px;margin-bottom:10px;">
            </div>
        </a>
        @if(request()->is("admin"))
            <div class="list-group list-group-flush">
                <br>
                <h3 class="m-3 font-weight-bold" style="font-size:18px;">
                    <small class="text-muted">ADMINISTRATOR</small>
                </h3>
                <a class="list-group-item list-group-item-custom active" data-toggle="list" href="#transactions"
                   style="cursor:pointer;">
                    <i class="fas fa-balance-scale p-2" style="margin-right:10px;"></i>
                    Transactions
                </a>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#users"
                   style="cursor:pointer;">
                    <i class="fas fa-users p-2" style="margin-right:10px;"></i>
                    Users
                </a>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#versions"
                   style="cursor:pointer;">
                    <i class="fas fa-download p-2" style="margin-right:10px;"></i>
                    Versions
                </a>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#referral-codes"
                   style="cursor:pointer;">
                    <i class="fas fa-user-tag p-2" style="margin-right:10px;"></i>
                    Referral Codes
                </a>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#game-sessions"
                   style="cursor:pointer;">
                    <i class="fas fa-chart-area p-2" style="margin-right:10px;"></i>
                    Game Sessions
                </a>
            </div>
        @else
            <div class="list-group list-group-flush">
                <h3 class="m-3 font-weight-bold" style="font-size:18px;">
                    <small class="text-white">SETTINGS</small>
                </h3>
                <a class="list-group-item list-group-item-custom active" data-toggle="list" href="#profile"
                   style="cursor:pointer;">
                    <i class="fas fa-user-circle p-2" style="margin-right:10px;"></i>
                    Profile
                </a>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#security"
                   style="cursor:pointer;">
                    <i class="fas fa-lock p-2" style="margin-right:10px;"></i>
                    Security
                </a>
                <a class="list-group-item list-group-item-custom" href="https://forums.envyclient.com/"
                   style="cursor:pointer;">
                    <i class="fas fa-comments p-2" style="margin-right:10px;"></i>
                    Forums
                </a>
                <h3 class="m-3 font-weight-bold" style="font-size:18px;padding-top:30px;">
                    <small class="text-white">BILLING</small>
                </h3>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#subscription"
                   style="cursor:pointer;">
                    <i class="fas fa-redo p-2" style="margin-right:10px;"></i>
                    Subscription
                </a>
           {{--     <a class="list-group-item list-group-item-custom" data-toggle="list" href="#credits"
                   style="cursor:pointer;">
                    <i class="fas fa-credit-card p-2" style="margin-right:10px;"></i>
                    Add Credits
                </a>
                @if(count($transactions) > 0)
                    <a class="list-group-item list-group-item-custom" data-toggle="list" href="#invoices"
                       style="cursor:pointer;">
                        <i class="fas fa-shopping-cart p-2" style="margin-right:10px;"></i>
                        Transactions
                    </a>
                @endif--}}
            </div>
        @endif
    </div>
</div>
