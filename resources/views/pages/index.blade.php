@extends('layouts.dash')

@section('content')
    <div style="width:95%;margin:0 auto">

        <!--- Profile Section --->
        <div class="alert alert-secondary" style="font-size:25px;">
            <i class="fas fa-user" style="padding-right:10px;"></i> Profile
        </div>

        <div class="text-left">
            <label class="form-label">Member Since</label>
            <input class="form-control" placeholder="Date" readonly
                   value="{{ $user->created_at->format('Y-m-d') }}">
        </div>
        <br>
        <div>
            @if($user->hasSubscription())
                <div class="alert alert-secondary" style="font-size:25px;">
                    <i class="fas fa-file" style="padding-right:10px;"></i> Configs
                    <span class="badge badge-secondary">
                        {{ $configs->count() }}/{{ $user->getConfigLimit() }}
                    </span>
                </div>
                <div class="text-left">
                    @if(count($configs) > 0)
                        <div class="table-responsive table-sticky" style="overflow-y: scroll;max-height: 400px;">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">name</th>
                                    <th scope="col">public</th>
                                    <th scope="col">favorites</th>
                                    <th scope="col">created</th>
                                    <th scope="col">last updated</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($configs as $config)
                                    <tr>
                                        <th scope="row">{{ $loop->index + 1 }}</th>
                                        <td>{{ $config->name }}</td>
                                        <td>{{ $config->public ? 'true' : 'false' }}</td>
                                        <td>{{ $config->favorites_count }}</td>
                                        <td>{{ $config->created_at->diffForHumans() }}</td>
                                        <td>{{ $config->updated_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <a class="btn btn-primary btn-lg btn-block mt-3" href="{{ route('versions.launcher') }}">
                    <i class="fas fa-download pr-1"></i> Download Launcher
                </a>
            @endif
        </div>
    </div>
@endsection
