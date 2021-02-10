<div style="width:98%;margin:0 auto">

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col input-group">
            <label class="input-group-text" for="status">Status</label>
            <select class="form-select" id="status" wire:model="status">
                <option value="all" selected>All</option>
                <option value="pending">Pending</option>
                <option value="extended">Extended</option>
                <option value="approved">Approved</option>
            </select>
        </div>
    </div>

    <br>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>user</th>
            <th>status</th>
            <th>channel</th>
            <th>action_reason</th>
            <th>actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($requests as $request)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>
                    Name: <strong>{{ $request->user->name }}</strong>
                    @if($request->user->subscription !== null)
                        <br>
                        Plan: <strong>{{ $request->user->subscription->plan->name }}</strong>
                        <br>
                        Expires: <strong>{{ $request->user->subscription->end_date->diffInDays() }}</strong> days
                    @endif
                </td>
                <td>
                    <x-license-request-status :status="$request->status"/>
                </td>
                <td>
                    <a href="{{ $request->channel }}">{{ $request->channel }}</a>
                </td>
                <td>{{ $request->action_reason }}</td>
                <td>
                    @if($request->status === 'pending')
                        <button class="btn btn-outline-success" wire:click="approve({{ $request->id }})">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"/>
                            </svg>
                        </button>
                        <button class="btn btn-outline-danger"
                                onclick="window.livewire.emit('DENY_REQUEST', {id : {{ $request->id }}, message: prompt('Reason')});">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M15.59,7L12,10.59L8.41,7L7,8.41L10.59,12L7,15.59L8.41,17L12,13.41L15.59,17L17,15.59L13.41,12L17,8.41L15.59,7Z"/>
                            </svg>
                        </button>
                    @elseif($request->status === 'approved')
                        <button class="btn btn-outline-info" wire:click="extend({{ $request->id }})">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M19,8L15,12H18A6,6 0 0,1 12,18C11,18 10.03,17.75 9.2,17.3L7.74,18.76C8.97,19.54 10.43,20 12,20A8,8 0 0,0 20,12H23M6,12A6,6 0 0,1 12,6C13,6 13.97,6.25 14.8,6.7L16.26,5.24C15.03,4.46 13.57,4 12,4A8,8 0 0,0 4,12H1L5,16L9,12"/>
                            </svg>
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @if($editMode)
        asd
    @endif
</div>
