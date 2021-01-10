<div class="container" wire:poll>
    <h2 class="text-center">
        Process Payment - <span class="text-muted">{{ $source->plan->name }} Plan</span>
    </h2>
    <hr>
    <div class="row">
        <div class="col">
            <h3>Events</h3>
            <table class="table">
                <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">type</th>
                    <th scope="col">message</th>
                    <th scope="col">time</th>
                </tr>
                </thead>
                <tbody>
                @foreach($events as $event)
                    <tr>
                        <th scope="row">{{ $loop->index + 1 }}</th>
                        <td>
                            @if($event->type === 'pending')
                                <span class="badge rounded-pill bg-primary">pending</span>
                            @elseif($event->type === 'canceled')
                                <span class="badge rounded-pill bg-warning text-dark">canceled</span>
                            @elseif($event->type === 'failed')
                                <span class="badge rounded-pill bg-danger">failed</span>
                            @elseif($event->type === 'chargeable')
                                <span class="badge rounded-pill bg-info text-dark">chargeable</span>
                            @elseif($event->type === 'succeeded')
                                <span class="badge rounded-pill bg-success">succeeded</span>
                            @endif
                        </td>
                        <td>{{ $event->message }}</td>
                        <td>{{ $event->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col">
            <h3>Scan QR Code</h3>
            <img src="https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl={{ urlencode($source->url) }}"
                 class="rounded"
                 alt="qr code">
        </div>

        @if($source->status !== 'pending' && $source->status !== 'chargeable')
            <div class="d-grid gap-2">
                <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}">Dashboard</a>
            </div>
        @endif
    </div>
</div>
