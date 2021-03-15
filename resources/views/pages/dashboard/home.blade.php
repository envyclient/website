@extends('layouts.dash')

@section('title', 'Home')

@section('content')

    <div class="row">

        <!--- Profile Section --->
        <div class="col-md">
            <div class="alert alert-dark" style="font-size:25px;">
                <svg class="pb-1" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                          d="M8 3.293l6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
                    <path fill-rule="evenodd"
                          d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
                </svg>
                Home
            </div>
            <div class="card shadow-sm">
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
                        @livewire('upload-cape')
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md">
            <div class="alert alert-dark" style="font-size:25px;">
                <svg class="pb-1" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.122C.002 7.343.01 6.6.064 5.78l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                </svg>
                License Request
            </div>
            <div class="card shadow-sm">
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
                                <label class="form-label" for="channel">YouTube Channel Link</label>
                                <input id="channel"
                                       name="channel"
                                       class="form-control"
                                       type="text"
                                       required>
                                <div class="form-text">
                                    Must be Minecraft related and have more than 200 subs.
                                </div>
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
                        <h5 class="modal-title">Download Launcher</h5>
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
@endsection
