<span
    x-data="{ open: false, message: null }"
    x-init="
        @this.on('small-notify', (m) => {
            if (open === false) setTimeout(() => { open = false; message = null; }, 3000);
                open = true;
                message = m;
            })
    "
    x-show.transition.out.duration.1000ms="open"
    x-text="message"
    style="display: none;"
    {{ $attributes->merge(['class' => 'text-sm']) }}
></span>
