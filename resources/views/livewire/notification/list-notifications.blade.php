<div>
    @if(count($notifications) > 0)
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>type</th>
                <th>data</th>
                <th>created</th>
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
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $notifications->links() }}
    @endif
</div>
