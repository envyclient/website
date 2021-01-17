@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">
        <div class="alert alert-dark" style="font-size:25px;">
            Sales
        </div>

        <div class="text-center" style="width:100%;display:block;margin:0 auto;">
            <div class="col-md-3" style="display:inline-block;margin:0 auto;">
                <div class="card text-center" style="padding:10px;">
                    <i class="fas fa-coins fa-2x"></i>
                    <h2>$xx.xx</h2>
                    <h4>Total</h4>
                </div>
            </div>
            <div class="col-md-3" style="display:inline-block;margin:0 auto;">
                <div class="card text-center" style="padding:10px;">
                    <i class="fas fa-coins fa-2x"></i>
                    <h2>$xx.xx</h2>
                    <h4>Standard</h4>
                </div>
            </div>
            <div class="col-md-3" style="display:inline-block;margin:0 auto;">
                <div class="card text-center" style="padding:10px;">
                    <i class="fas fa-coins fa-2x"></i>
                    <h2>$xx.xx</h2>
                    <h4>Premium</h4>
                </div>
            </div>
        </div>

        <x-bar-chart name="sales_chart"/>
    </div>
@endsection
