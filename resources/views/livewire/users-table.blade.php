<div>
    <!-- filters -->
    <div class="form-row">
        <div class="col">
            <input class="form-control" type="text" placeholder="Name" wire:model="name">
        </div>
        <div class="col">
            <select class="form-control" wire:model="type">
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
                        Expires in <strong>{{ $user->subscription->end_date->diffForHumans() }}</strong>
                    </td>
                @else
                    <td>No Subscription</td>
                @endif
                <td>
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
