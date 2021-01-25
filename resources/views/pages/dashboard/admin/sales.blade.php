@extends('layouts.dash')

@section('title', 'Sales')

@section('content')
    <div class="m-0 m-auto" style="width:98%;">
        <div class="text-center w-100 mb-3" style="display:block;">
            <div class="w-50 m-1" style="display:inline-block;">
                <div class="text-center shadow p-3 bg-light rounded">
                    <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                        <path fill="currentColor"
                              d="M5,6H23V18H5V6M14,9A3,3 0 0,1 17,12A3,3 0 0,1 14,15A3,3 0 0,1 11,12A3,3 0 0,1 14,9M9,8A2,2 0 0,1 7,10V14A2,2 0 0,1 9,16H19A2,2 0 0,1 21,14V10A2,2 0 0,1 19,8H9M1,10H3V20H19V22H1V10Z"/>
                    </svg>
                    <h2>${{ $total }}</h2>
                    <h4>Total</h4>
                </div>
            </div>
            <br>
            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center text-white" style="background-color: #5470C6">
                    <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                        <path fill="currentColor"
                              d="M5,6H23V18H5V6M14,9A3,3 0 0,1 17,12A3,3 0 0,1 14,15A3,3 0 0,1 11,12A3,3 0 0,1 14,9M9,8A2,2 0 0,1 7,10V14A2,2 0 0,1 9,16H19A2,2 0 0,1 21,14V10A2,2 0 0,1 19,8H9M1,10H3V20H19V22H1V10Z"/>
                    </svg>
                    <h2>${{ $methods['paypal'] }}</h2>
                    <h4>Paypal</h4>
                </div>
            </div>
            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center text-white" style="background-color:#91CC75;">
                    <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                        <path fill="currentColor"
                              d="M9.5,4C5.36,4 2,6.69 2,10C2,11.89 3.08,13.56 4.78,14.66L4,17L6.5,15.5C7.39,15.81 8.37,16 9.41,16C9.15,15.37 9,14.7 9,14C9,10.69 12.13,8 16,8C16.19,8 16.38,8 16.56,8.03C15.54,5.69 12.78,4 9.5,4M6.5,6.5A1,1 0 0,1 7.5,7.5A1,1 0 0,1 6.5,8.5A1,1 0 0,1 5.5,7.5A1,1 0 0,1 6.5,6.5M11.5,6.5A1,1 0 0,1 12.5,7.5A1,1 0 0,1 11.5,8.5A1,1 0 0,1 10.5,7.5A1,1 0 0,1 11.5,6.5M16,9C12.69,9 10,11.24 10,14C10,16.76 12.69,19 16,19C16.67,19 17.31,18.92 17.91,18.75L20,20L19.38,18.13C20.95,17.22 22,15.71 22,14C22,11.24 19.31,9 16,9M14,11.5A1,1 0 0,1 15,12.5A1,1 0 0,1 14,13.5A1,1 0 0,1 13,12.5A1,1 0 0,1 14,11.5M18,11.5A1,1 0 0,1 19,12.5A1,1 0 0,1 18,13.5A1,1 0 0,1 17,12.5A1,1 0 0,1 18,11.5Z"/>
                    </svg>
                    <h2>${{ $methods['wechat'] }}</h2>
                    <h4>WeChat Pay</h4>
                </div>
            </div>
            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center text-white" style="background-color:#FAC858;">
                    <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                        <path fill="currentColor"
                              d="M14.24 10.56C13.93 11.8 12 11.17 11.4 11L11.95 8.82C12.57 9 14.56 9.26 14.24 10.56M11.13 12.12L10.53 14.53C11.27 14.72 13.56 15.45 13.9 14.09C14.26 12.67 11.87 12.3 11.13 12.12M21.7 14.42C20.36 19.78 14.94 23.04 9.58 21.7C4.22 20.36 .963 14.94 2.3 9.58C3.64 4.22 9.06 .964 14.42 2.3C19.77 3.64 23.03 9.06 21.7 14.42M14.21 8.05L14.66 6.25L13.56 6L13.12 7.73C12.83 7.66 12.54 7.59 12.24 7.53L12.68 5.76L11.59 5.5L11.14 7.29C10.9 7.23 10.66 7.18 10.44 7.12L10.44 7.12L8.93 6.74L8.63 7.91C8.63 7.91 9.45 8.1 9.43 8.11C9.88 8.22 9.96 8.5 9.94 8.75L8.71 13.68C8.66 13.82 8.5 14 8.21 13.95C8.22 13.96 7.41 13.75 7.41 13.75L6.87 15L8.29 15.36C8.56 15.43 8.82 15.5 9.08 15.56L8.62 17.38L9.72 17.66L10.17 15.85C10.47 15.93 10.76 16 11.04 16.08L10.59 17.87L11.69 18.15L12.15 16.33C14 16.68 15.42 16.54 16 14.85C16.5 13.5 16 12.7 15 12.19C15.72 12 16.26 11.55 16.41 10.57C16.61 9.24 15.59 8.53 14.21 8.05Z"/>
                    </svg>
                    <h2>${{ $methods['crypto'] }}</h2>
                    <h4>Crypto</h4>
                </div>
            </div>
            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center text-white" style="background-color:#ee6666;">
                    <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                        <path fill="currentColor"
                              d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z"/>
                    </svg>
                    <h2>${{ $methods['stripe'] }}</h2>
                    <h4>Stripe</h4>
                </div>
            </div>
        </div>

        <x-bar-chart name="sales_chart"/>
    </div>
@endsection
