<div>
    @if(count($versions) > 0)
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>name</th>
                <th>beta</th>
                <th>changelog</th>
                <th>created</th>
                <th>action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($versions as $version)
                @livewire('admin.version.version', ['index' => $loop->iteration,'version' => $version], key($version->id))
            @endforeach
            </tbody>
        </table>

        {{ $versions->links() }}
    @endif
</div>
