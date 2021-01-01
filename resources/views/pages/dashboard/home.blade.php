@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">

        <div class="row">

            <div class="col">
                <!--- Profile Section --->
                <div class="alert alert-secondary" style="font-size:25px;">
                    <i class="fas fa-user" style="padding-right:10px;"></i>
                    Profile
                </div>

                <div class="mb-3">
                    <label class="form-label" for="member-since">Member Since</label>
                    <input id="member-since"
                           class="form-control"
                           value="{{ $user->created_at->format('Y-m-d') }}"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="referral-code">Referral Code</label>
                    <input id="referral-code"
                           class="form-control"
                           value="{{ $user->referralCode()->exists() ? $user->referralCode->code : 'none used' }}"
                           readonly>
                </div>

                @if($user->hasCapesAccess())
                    <div class="mt-3">
                        <label class="form-label">Cape</label>
                        <br>
                        <img src="{{ $user->cape }}"
                             alt="cape"
                             class="rounded"
                             width="256px"
                             height="128px"
                        >

                        <form action="{{ route('capes.store') }}"
                              method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <input class="form-control"
                                       type="file"
                                       id="cape"
                                       name="cape"
                                       accept=".png"
                                       required>
                                <div class="form-text">
                                    The dimensions must be 2048x1048.
                                </div>


                                @error('cape')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success inline">Upload</button>
                            <button type="button" class="btn btn-outline-secondary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#capes-modal">
                                Actions
                            </button>
                        </form>
                    </div>
                @endif

            </div>

            @if($user->hasSubscription())
                <div class="col">
                    <div class="alert alert-secondary" style="font-size:25px;">
                        <i class="fas fa-file" style="padding-right:10px;"></i> Configs
                        <span class="badge bg-secondary">
                        {{ count($configs) }}/{{ $user->getConfigLimit() }}
                    </span>
                    </div>
                    @if(count($configs) > 0)
                        <div class="table-responsive table-sticky" style="overflow-y: scroll;max-height: 400px;">
                            <table class="table table-bordered">
                                <thead class="table-light">
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
                                        <td>
                                            @if($config->public)
                                                &#10006;
                                            @else
                                                &#10004;
                                            @endif
                                        </td>
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
            @endif
        </div>

        <div class="d-grid gap-2 mt-3">
            <button type="button"
                    class="btn btn-primary btn-lg"
                    data-bs-toggle="modal"
                    data-bs-target="#launcher-modal">
                <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="mb-1 mr-1">
                    <path fill="currentColor"
                          d="M17,13L12,18L7,13H10V9H14V13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z"/>
                </svg>
                Download Launcher
            </button>
        </div>

    </div>

    @if($user->hasSubscription())
        <!-- Launcher Modal -->
        <div class="modal fade"
             id="launcher-modal"
             data-bs-backdrop="static"
             data-bs-keyboard="false"
             tabindex="-1"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Launcher</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-dark" role="alert">
                            For video instructions please watch <a class="alert-link" target="_blank"
                                                                   href="https://www.youtube.com/watch?v=OXr9YGbxIlU">Install
                                Envy Client</a>.
                        </div>
                        <div class="alert alert-warning " role="alert">
                            Make sure to install <strong>Microsoft Visual C++ 2015 Redistributable</strong>.
                            You can download
                            <a class="alert-link" target="_blank"
                               href="https://www.microsoft.com/en-us/download/details.aspx?id=53587">here</a> (<a
                                class="alert-link" href="https://aka.ms/vs/16/release/vc_redist.x64.exe">mirror</a>).
                        </div>

                        <p>
                            You are downloading an <strong>unsigned version</strong> of the launcher.
                            Make sure you <strong>accept</strong> all warning messages.
                            In future updates, these warnings will go away.
                        </p>

                        <div class="row">
                            <div class="col">
                                <h4>Microsoft Edge</h4>
                                <img src="{{ asset('assets/launcher/1.png') }}" class="rounded" width="312"
                                     height="330">
                            </div>

                            <div class="col">
                                <h4>Windows Defender</h4>
                                <img src="{{ asset('assets/launcher/2.png') }}" class="rounded" width="416"
                                     height="390">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer card-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a role="button" class="btn btn-primary" href="{{ route('launcher.show') }}">
                            Download Launcher
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($user->hasCapesAccess())
        <!-- Capes Modal -->
        <div class="modal fade" id="capes-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Capes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div>
                            <h3>Cape Template</h3>
                            <a class="btn btn-outline-dark d-inline-block"
                               href="{{ asset('assets/capes/template.png') }}">
                                Download
                            </a>
                        </div>

                        <div class="mt-3">
                            <h3>Reset to default cape</h3>
                            <form action="{{ route('capes.delete') }}"
                                  method="post"
                                  class="d-inline-block">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-outline-danger">Reset Cape</button>
                            </form>
                        </div>

                    </div>
                    <div class="modal-footer card-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
