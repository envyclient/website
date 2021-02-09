<div>
    @if(count($codes) > 0)
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>user</th>
                <th>code</th>
                <th>uses</th>
                <th>subscriptions</th>
                <th>last usage</th>
                <th>created</th>
            </tr>
            </thead>
            <tbody>
            @foreach($codes as $code)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $code->user->name }}</td>
                    <td>{{ $code->code }}</td>
                    <td>
                        @if(count($code->users) > 0)
                            <a href="#"
                               data-bs-toggle="modal"
                               data-bs-target="#list-referred-users"
                               wire:click="showModal({{ $code->id }})">
                                {{ $code->users->count() }}
                            </a>
                        @else
                            &#10006;
                        @endif
                    </td>
                    <td>{{ $code->subscriptions->count() }}</td>
                    <td>
                        @if($code->users->last() === null)
                            &#10006;
                        @else
                            <strong>User</strong>: {{ $code->users->last()->name }}
                            <br>
                            <strong>Time</strong>: {{ $code->users->last()->referral_code_used_at->diffForHumans()  }}
                        @endif
                    </td>
                    <td>{{ $code->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $codes->links() }}

        @if($showModal)
            <div class="modal"
                 id="list-referred-users"
                 role="dialog"
                 tabindex="-1"
                 style="display: block;">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Users</h5>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-sm">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">user</th>
                                    <th scope="col">subscription</th>
                                    <th scope="col">expires</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($showingUsers as $user)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->subscription?->plan->name }}</td>
                                        <td>{{ $user->subscription?->end_date->diffInDays() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer card-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @endif
</div>
