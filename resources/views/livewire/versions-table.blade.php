<div>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>name</th>
            <th>beta</th>
            <th>version</th>
            <th>assets</th>
            <th>date</th>
            <th>action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($versions as $version)
            <tr>
                <th scope="row">{{ $loop->index + 1 }}</th>
                <td>{{ $version->name }}</td>
                <td>
                    @if($version->beta)
                        &#10004;
                    @else
                        &#10006;
                    @endif
                </td>
                <td>{{ $version->version }}</td>
                <td>{{ $version->assets }}</td>
                <td>{{ $version->created_at->diffForHumans() }}</td>
                <td>
                    <a type="button" class="btn btn-danger" role="button"
                       wire:click="deleteVersion({{ $version->id }})">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                  d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                        </svg>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
