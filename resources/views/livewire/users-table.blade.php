<div>
    <!-- filters -->
    <div class="row">
        <div class="col">
            <input class="form-control" type="text" placeholder="Name" wire:model="name">
        </div>
        <div class="col">
            <select class="form-select" wire:model="type">
                <option value="all">All</option>
                <option value="banned">Only Banned</option>
            </select>
        </div>
    </div>

    <br>

    <!-- users table -->
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>name</th>
            <th>email</th>
            <th>subscription</th>
            <th>hwid</th>
            <th>banned</th>
            <th>actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <th scope="row">{{ $loop->index + 1 }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                @if($user->hasSubscription())
                    <td>
                        Plan: <strong>{{ $user->subscription->plan->name }}</strong>
                        <br>
                        Expires: <strong>{{ $user->subscription->end_date->diffInDays() }}</strong> days
                    </td>
                @else
                    <td>No Subscription</td>
                @endif
                <td>
                    @if($user->hwid === null)
                        &#10006;
                    @else
                        &#10004;
                    @endif
                </td>
                <td>
                    @if($user->banned)
                        &#10004;
                    @else
                        &#10006;
                    @endif
                </td>
                <td>
                    @if($user->hwid !== null)
                        <a class="btn btn-outline-secondary" wire:click="resetUserHWID({{ $user->id}})">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M12 9A1 1 0 1 0 13 10A1 1 0 0 0 12 9M12 9A1 1 0 1 0 13 10A1 1 0 0 0 12 9M18 2H6A2 2 0 0 0 4 4V13.09A5.47 5.47 0 0 1 5 13A5.71 5.71 0 0 1 7 13.36A6 6 0 1 1 15.71 14.69L13.79 12.27A1 1 0 0 0 12.42 11.9L11.56 12.4A1 1 0 0 0 11.19 13.77L12.1 16A6.12 6.12 0 0 1 10 15.62A6 6 0 0 1 10.19 22H18A2 2 0 0 0 20 20V4A2 2 0 0 0 18 2M14.58 19.58L12.09 13.27L12.95 12.77L17.17 18.08M12 11A1 1 0 1 0 11 10A1 1 0 0 0 12 11M7.12 22.54L5 20.41L2.88 22.54L1.46 21.12L3.59 19L1.46 16.88L2.88 15.46L5 17.59L7.12 15.46L8.54 16.88L6.41 19L8.54 21.12Z"/>
                            </svg>
                        </a>
                    @endif
                    @if($user->banned)
                        <a class="btn btn-outline-success text-success" wire:click="banUser({{ $user->id}})">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M11 6H14L17.29 2.7A1 1 0 0 1 18.71 2.7L21.29 5.29A1 1 0 0 1 21.29 6.7L19 9H11V11A1 1 0 0 1 10 12A1 1 0 0 1 9 11V8A2 2 0 0 1 11 6M5 11V15L2.71 17.29A1 1 0 0 0 2.71 18.7L5.29 21.29A1 1 0 0 0 6.71 21.29L11 17H15A1 1 0 0 0 16 16V15H17A1 1 0 0 0 18 14V13H19A1 1 0 0 0 20 12V11H13V12A2 2 0 0 1 11 14H9A2 2 0 0 1 7 12V9Z"/>
                            </svg>
                        </a>
                    @else
                        <a class="btn btn-outline-danger text-danger" wire:click="banUser({{ $user->id }})">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M12 2C17.5 2 22 6.5 22 12S17.5 22 12 22 2 17.5 2 12 6.5 2 12 2M12 4C10.1 4 8.4 4.6 7.1 5.7L18.3 16.9C19.3 15.5 20 13.8 20 12C20 7.6 16.4 4 12 4M16.9 18.3L5.7 7.1C4.6 8.4 4 10.1 4 12C4 16.4 7.6 20 12 20C13.9 20 15.6 19.4 16.9 18.3Z"/>
                            </svg>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
