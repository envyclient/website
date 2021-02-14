@props(['plan'])

<div {{ $attributes->merge(['class' => 'card shadow-sm']) }}>
    <div class="card-body">
        <h4 class="mb-3">{{ $plan->name }}</h4>
        <h6 class="mb-3">
            <span class="fs-4">${{ $plan->price }}</span> USD / monthly
        </h6>

        <div class="mb-2">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="green">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"/>
            </svg>
            <span class="ms-1">Monthly Updates</span>
        </div>

        <div class="mb-2">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="green">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"/>
            </svg>
            <span class="ms-1">Killer Features</span>
        </div>

        <div class="mb-2">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="green">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"/>
            </svg>
            <span class="ms-1">{{ $plan->config_limit }} Configs</span>
        </div>

        @if($plan->beta_access)
            <div class="mb-2">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="green">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"/>
                </svg>
                <span class="ms-1">Beta Access</span>
            </div>
        @endif

        @if($plan->capes_access)
            <div class="mb-2">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="green">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"/>
                </svg>
                <span class="ms-1">Capes Access</span>
            </div>
        @endif

        <div>
            <svg style="width:20px;height:20px" viewBox="0 0 20 20" fill="#eb5e0b">
                <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                      clip-rule="evenodd"/>
            </svg>
            <span class="ms-1">Windows Only</span>
        </div>
    </div>

    {{ $slot }}
</div>
