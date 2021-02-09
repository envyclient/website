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
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $code->user->name }}</td>
                    <td>{{ $code->code }}</td>
                    <td>{{ $code->users->count() }}</td>
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
    @endif
</div>
