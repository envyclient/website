<!-- Transactions Section -->
<div class="tab-pane fade" id="invoices" role="tabpanel">
    <div class="alert alert-secondary" style="font-size:25px;">
        <i class="fas fa-shopping-cart" style="padding-right:10px;"></i> Transactions
    </div>
    <table class="table table-bordered table-striped" style="background:white;">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Amount</th>
            <th scope="col">Description</th>
            <th scope="col">Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <th scope="row">{{ $loop->index + 1 }}</th>
                @if($transaction->type === 'deposit')
                    <td style="color: green">+{{ $transaction->amount }}</td>
                @else
                    <td style="color: red">-{{ $transaction->amount }}</td>
                @endif
                <td>{{ $transaction->meta['description'] }}</td>
                <td>{{ $transaction->created_at->diffForHumans() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
