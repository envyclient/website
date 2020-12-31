<div>
    @if(count($codes) > 0)
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>user</th>
                <th>code</th>
                <th>uses</th>
                <th>purchases</th>
                <th>last usage</th>
                <th>created</th>
            </tr>
            </thead>
            <tbody>
            @foreach($codes as $code)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $code->user->name }}</td>
                    <td>{{ $code->code }}</td>
                    <td>{{ $code->users->count() }}</td>
                    <td>{{ $code->users->where('subscription', '<>', null)->count() }}</td>
                    <td>{{ $code->users->last()?->created_at->diffForHumans() }}</td>
                    <td>{{ $code->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $codes->links() }}
    @endif
</div>
