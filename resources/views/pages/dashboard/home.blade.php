@extends('layouts.dash')

@section('title', 'Home')

@section('content')
    <div style="width:98%;margin:0 auto">

        <div class="row">

            <!--- Profile Section --->
            <div class="col">
                <div class="alert alert-dark" style="font-size:25px;">
                    <svg class="mb-1" style="width:32px;height:32px;" viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                    </svg>
                    Profile
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="member-since">Member Since</label>
                            <input id="member-since"
                                   class="form-control"
                                   value="{{ $user->created_at->format('F d, Y') }}"
                                   disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="referral-code">Referral Code</label>
                            @if($user->referral_code_id !== null)
                                <input id="referral-code"
                                       class="form-control"
                                       value="{{ $user->referralCode->code }}"
                                       disabled>
                            @else
                                <form action="{{ route('users.referral-code') }}" method="post">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               class="form-control"
                                               placeholder="code"
                                               id="referral-code"
                                               name="referral-code"
                                               required>
                                        <button type="submit" class="btn btn-success">Use Code</button>
                                    </div>
                                </form>
                            @endif
                        </div>

                        @if($user->hasCapesAccess())
                            <div class="mt-3">
                                <label class="form-label">Cape</label>
                                <br>
                                <img src="{{ $user->cape }}"
                                     alt="cape"
                                     class="rounded"
                                     width="256px"
                                     height="128px">

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
                </div>
            </div>

            <div class="col">
                <div class="alert alert-dark" style="font-size:25px;">
                    <svg class="mb-1" style="width:32px;height:32px;" viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M10,15L15.19,12L10,9V15M21.56,7.17C21.69,7.64 21.78,8.27 21.84,9.07C21.91,9.87 21.94,10.56 21.94,11.16L22,12C22,14.19 21.84,15.8 21.56,16.83C21.31,17.73 20.73,18.31 19.83,18.56C19.36,18.69 18.5,18.78 17.18,18.84C15.88,18.91 14.69,18.94 13.59,18.94L12,19C7.81,19 5.2,18.84 4.17,18.56C3.27,18.31 2.69,17.73 2.44,16.83C2.31,16.36 2.22,15.73 2.16,14.93C2.09,14.13 2.06,13.44 2.06,12.84L2,12C2,9.81 2.16,8.2 2.44,7.17C2.69,6.27 3.27,5.69 4.17,5.44C4.64,5.31 5.5,5.22 6.82,5.16C8.12,5.09 9.31,5.06 10.41,5.06L12,5C16.19,5 18.8,5.16 19.83,5.44C20.73,5.69 21.31,6.27 21.56,7.17Z"/>
                    </svg>
                    Media License Request
                </div>
                <div class="card">
                    <div class="card-body">
                        @if($user->licenseRequest !== null)
                            <div class="mb-3">
                                <label class="form-label" for="license-request-date">Request Time</label>
                                <input id="license-request-date"
                                       class="form-control"
                                       value="{{ $user->licenseRequest->created_at->diffForHumans() }}"
                                       disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="license-request-channel">Channel</label>
                                <input id="license-request-channel"
                                       class="form-control"
                                       value="{{ $user->licenseRequest->channel }}"
                                       disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="license-request-status">Status</label>
                                <h5>
                                    <x-license-request-status :status="$user->licenseRequest->status"/>
                                </h5>
                            </div>
                            @if($user->licenseRequest->action_at !== null)
                                <div class="mb-3">
                                    <label class="form-label" for="license-request-action-reason">Action Reason</label>
                                    <textarea id="license-request-action-reason"
                                              class="form-control"
                                              disabled>{{ $user->licenseRequest->action_reason }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="license-request-action-time">Action Time</label>
                                    <input id="license-request-action-time"
                                           class="form-control"
                                           value="{{ $user->licenseRequest->action_at->diffForHumans() }}"
                                           disabled>
                                </div>
                            @endif
                        @else
                            <form action="{{ route('users.license-request') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="channel">Channel</label>
                                    <input id="channel"
                                           name="channel"
                                           class="form-control"
                                           type="text"
                                           required>
                                </div>
                                <button type="submit" class="btn btn-success">Submit Request</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($user->subscription !== null)
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
        @endif

    </div>

    @if($user->subscription !== null)
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
                        </p>

                        <div class="row">
                            <div class="col">
                                <h4>Microsoft Edge</h4>
                                <img src="{{ asset('assets/launcher/edge.png') }}" class="rounded" width="312"
                                     height="330">
                            </div>

                            <div class="col">
                                <h4>Windows Defender</h4>
                                <img src="{{ asset('assets/launcher/defender.png') }}" class="rounded" width="416"
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
