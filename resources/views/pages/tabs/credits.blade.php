<!-- Add Credits Section -->
<div class="tab-pane fade" id="credits" role="tabpanel">
    <div class="alert alert-secondary" style="font-size:25px;">
        <i class="fas fa-credit-card" style="padding-right:10px;"></i> Add Credits
    </div>

    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <h5 class="font-weight-bold">Payment methods</h5>
            <h6>Currently we are only supporting PayPal payments</h6>
            <div class="container m-3 font-weight-bold">
                <p>Terms and Conditions</p>
                <ul class="font-weight-normal">
                    <li>
                        By adding credits you are agreeing to our
                        <a href="{{ route('terms') }}">Terms and Conditions.</a>
                    </li>
                </ul>
            </div>
            <div class="card" style="width: 100%;margin-top:10px;">
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: bold;">
                        <i class="fab fa-paypal"></i> PayPal
                    </h5>
                    {!! Form::open(['action' => 'PayPalController@create']) !!}
                    <div class="form-group">
                        {{ Form::label('amount', 'Amount:') }}
                        <br>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            @foreach([5, 10, 15, 20, 30] as $amount)
                                @if($loop->first)
                                    <label class="btn btn-secondary active">
                                        <input type="radio" name="amount" id="{{ $amount }}"
                                               value="{{ $amount }}" checked>
                                        ${{ $amount }}
                                    </label>
                                @else
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="amount" id="{{ $amount }}"
                                               value="{{ $amount }}">
                                        ${{ $amount }}
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    {{ Form::submit('Add Credits', ['class' => 'btn btn-primary']) }}
                    {!! Form::close() !!}
                </div>
            </div>
        </li>
    </ul>
</div>
