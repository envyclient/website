@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">

        <!--- Profile Section --->
        <div class="alert alert-secondary" style="font-size:25px;">
            <i class="fas fa-user" style="padding-right:10px;"></i> Profile
        </div>

        <div class="text-left">
            <label class="form-label" for="member-since">Member Since</label>
            <input id="member-since"
                   class="form-control"
                   placeholder="Date"
                   value="{{ $user->created_at->format('Y-m-d') }}"
                   readonly>
        </div>

        @if($user->hasSubscription())

            @if($user->hasCapesAccess())
                <div class="mt-3">
                    <label class="form-label">Cape</label>
                    <br>

                    <img
                        src="{{ $user->cape === null ? asset('assets/default_cape.png') : asset("storage/capes/$user->cape") }}"
                        alt="cape"
                        class="rounded"
                        width="128px"
                        height="128px"
                    >

                    <form action="{{ route('users.upload-cape') }}" method="post" enctype="multipart/form-data"
                          style="margin-top: 15px;">
                        @csrf
                        <div class="custom-file">
                            <input type="file"
                                   class="custom-file-input @error('cape') is-invalid @enderror"
                                   id="cape"
                                   name="cape"
                                   required>
                            <label class="custom-file-label" for="file">Choose file</label>

                            @error('cape')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success mt-2">Upload</button>
                    </form>
                </div>
            @endif

            <div class="alert alert-secondary mt-3" style="font-size:25px;">
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

            <a role="button" class="btn btn-primary btn-lg btn-block" href="{{ route('download-launcher') }}">
                <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="mb-1 mr-1">
                    <path fill="currentColor"
                          d="M17,13L12,18L7,13H10V9H14V13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z"/>
                </svg>
                Download Launcher
            </a>
        @endif

    </div>
@endsection

@section('js')
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            const fileName = document.getElementById("cape").files[0].name;
            const nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
@endsection
