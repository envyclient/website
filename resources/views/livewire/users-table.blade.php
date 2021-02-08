<div>
    <!-- filters -->
    <div class="row">
        <div class="col">
            <input class="form-control" type="text" placeholder="Name" wire:model="name">
        </div>
        <div class="col input-group">
            <label class="input-group-text" for="type">Status</label>
            <select class="form-select" id="type" wire:model="type">
                <option value="all" selected>All</option>
                <option value="subscribed">Subscribed</option>
                <option value="active-subscription">Active Subscription</option>
                <option value="cancelled-subscription">Cancelled Subscription</option>
                <option value="using-client">Using Client</option>
                <option value="banned">Banned</option>
            </select>
        </div>
        <div class="col input-group">
            <label class="input-group-text" for="subscription">Plan</label>
            <select class="form-select" id="subscription" wire:model="subscription">
                <option value="ignore" selected>Ignore</option>
                <option value="1">Free</option>
                <option value="2">Standard</option>
                <option value="3">Premium</option>
            </select>
        </div>
        <div class="col input-group">
            <label class="input-group-text" for="referral-code">Referral</label>
            <select class="form-select" id="referral-code" wire:model="referralCode">
                <option value="ignore" selected>Ignore</option>
                @foreach(\App\Models\ReferralCode::all() as $code)
                    <option value="{{ $code->id }}">{{ $code->code }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <br>

    <!-- users table -->
    <table class="table table-striped table-hover table-sm">
        <thead>
        <tr>
            <th>#</th>
            <th>name</th>
            <th>email</th>
            <th>subscription</th>
            <th>hwid</th>
            <th>banned</th>
            <th>current account</th>
            <th>discord</th>
            <th>referral code</th>
            <th>actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <th scope="row">{{ $user->id }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->subscription !== null)
                        Plan: <strong>{{ $user->subscription->plan->name }}</strong>
                        <br>
                        Expires: <strong>{{ now()->diffInDays($user->subscription->end_date, false) }}</strong> days
                    @else
                        &#10006;
                    @endif
                </td>
                <td>
                    @if($user->hwid === null)
                        &#10006;
                    @else
                        &#10004;
                    @endif
                </td>
                <td>
                    @if($user->banned)
                        &#10004;
                    @else
                        &#10006;
                    @endif
                </td>
                <td>
                    @if($user->current_account !== null)
                        <img
                            src="https://crafatar.com/avatars/{{ $user->current_account }}?size=32&default=MHF_Steve"
                            alt="head">
                    @else
                        &#10006;
                    @endif
                </td>
                <td>
                    @if($user->discord_id !== null)
                        {{ $user->discord_name }}
                    @else
                        &#10006;
                    @endif
                </td>
                <td>
                    @if($user->referralCode != null)
                        {{ $user->referralCode->code }}
                    @else
                        &#10006;
                    @endif
                </td>
                <td>
                    <button class="btn btn-outline-dark" wire:click="editMode({{ $user->id }})"
                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                  d="M14.06,9L15,9.94L5.92,19H5V18.08L14.06,9M17.66,3C17.41,3 17.15,3.1 16.96,3.29L15.13,5.12L18.88,8.87L20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18.17,3.09 17.92,3 17.66,3M14.06,6.19L3,17.25V21H6.75L17.81,9.94L14.06,6.19Z"/>
                        </svg>
                    </button>
                    @if($user->subscription !== null)
                        <a class="btn btn-outline-dark"
                           onclick="confirm('Remove Subscription?') || event.stopImmediatePropagation()"
                           wire:click="freeSubscription({{ $user->id }}, true)">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M3,4.27L4.28,3L21,19.72L19.73,21L16.06,17.33C15.44,18 14.54,18.55 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15H9C9,16.08 10.37,17 12,17C13.13,17 14.14,16.56 14.65,15.92L11.68,12.95C9.58,12.42 7,11.75 7,9C7,8.77 7,8.55 7.07,8.34L3,4.27M10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C11.63,7 11.28,7.05 10.95,7.13L9.4,5.58L10.5,5.18Z"/>
                            </svg>
                        </a>
                    @else
                        <a class="btn btn-outline-dark"
                           onclick="confirm('Give Subscription?') || event.stopImmediatePropagation()"
                           wire:click="freeSubscription({{ $user->id }})">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M7,15H9C9,16.08 10.37,17 12,17C13.63,17 15,16.08 15,15C15,13.9 13.96,13.5 11.76,12.97C9.64,12.44 7,11.78 7,9C7,7.21 8.47,5.69 10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C10.37,7 9,7.92 9,9C9,10.1 10.04,10.5 12.24,11.03C14.36,11.56 17,12.22 17,15C17,16.79 15.53,18.31 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15Z"/>
                            </svg>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $users->links() }}

    @if($editMode)
        <form wire:submit.prevent="save">
            <div class="modal"
                 data-bs-backdrop="static"
                 data-bs-keyboard="false"
                 tabindex="-1"
                 style="display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Edit User</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input id="name"
                                       class="form-control @error('editing.name') is-invalid @enderror"
                                       wire:model.defer="editing.name">

                                @error('editing.name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-check form-switch">
                                <label class="form-label" for="hwid">HWID</label>
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="hwid"
                                       wire:model.defer="editing.hwid">
                            </div>

                            <div class="form-check form-switch">
                                <label class="form-label" for="banned">Banned</label>
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="banned"
                                       wire:model.defer="editing.banned">
                            </div>
                        </div>
                        <div class="modal-footer card-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal"
                                    wire:click="$set('editMode', false)">
                                Close
                            </button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif

</div>
