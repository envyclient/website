<div style="width:98%;margin:0 auto">

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

    <table class="table table-striped table-hover table-sm">
        <thead>
        <tr>
            <th>#</th>
            <th>user</th>
            <th>status</th>
            <th>action_reason</th>
            <th>actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($requests as $request)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $request->user->name }}</td>
                <td>
                    <x-license-request-status :status="$request->status"/>
                </td>
                <td>{{ $request->action_reason }}</td>
                <td>
                    <button class="btn btn-outline-dark" wire:click="approve({{ $request->id }})">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                  d="M14.06,9L15,9.94L5.92,19H5V18.08L14.06,9M17.66,3C17.41,3 17.15,3.1 16.96,3.29L15.13,5.12L18.88,8.87L20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18.17,3.09 17.92,3 17.66,3M14.06,6.19L3,17.25V21H6.75L17.81,9.94L14.06,6.19Z"/>
                        </svg>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @if($editMode)
asd
    @endif
</div>
