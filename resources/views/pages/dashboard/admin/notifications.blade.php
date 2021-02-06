@extends('layouts.dash')

@section('title', 'Notifications')

@section('content')
    <div style="width:98%;margin:0 auto">
        <div>
            <div class="alert alert-dark" style="font-size:25px;">
                Notifications
            </div>

            @if(count($notifications) > 0)
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>type</th>
                        <th>data</th>
                        <th>created</th>
                        <th>action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($notifications as $notification)
                        <tr>
                            <th scope="row">{{ $loop->index + 1 }}</th>
                            <td>{{ $notification->type }}</td>
                            <td>
                                <code>
                                    {{ json_encode($notification->data) }}
                                </code>
                            </td>
                            <td>{{ $notification->created_at->diffForHumans() }}</td>
                            <td>
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="_method" value="delete">
                                    <button type="submit" class="btn btn-outline-danger">
                                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                  d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                                        </svg>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $notifications->links() }}
            @endif
        </div>

        <div>
            <div class="alert alert-dark" style="font-size:25px;">
                Create Notification
            </div>

            <form action="{{ route('notifications.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="type">Type</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                        <option value="info" selected>Info</option>
                        <option value="warning">Warning</option>
                    </select>

                    @error('type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="notification">Notification Message</label>
                    <textarea class="form-control @error('notification') is-invalid @enderror"
                              id="notification"
                              name="notification"
                              required></textarea>

                    @error('notification')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Create</button>
            </form>
        </div>
    </div>
@endsection
