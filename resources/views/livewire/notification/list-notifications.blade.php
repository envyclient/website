<div>
    @if(count($notifications) > 0)
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>version</th>
                <th>message</th>
                <th>created</th>
            </tr>
            </thead>
            <tbody>
            @foreach($notifications as $notification)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $notification->data['version'] }}</td>
                    <td>{{ $notification->data['message'] }}</td>
                    <td>{{ $notification->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $notifications->links() }}
    @endif
</div>
