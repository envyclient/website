@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">
        <!-- versions table -->
        <div>
            <div class="alert alert-primary" style="font-size:25px;">
                Versions
            </div>
            <div style="height: 300px">
                {!! $chart->container() !!}
            </div>

            @livewire('versions-table')
        </div>

        <br>

        <!-- create version -->
        <div>
            <div class="alert alert-secondary" style="font-size:25px;">
                Create Version
            </div>

            <form method="POST" action="{{ route('versions.store') }}" accept-charset="UTF-8"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" placeholder="Name" required="required" name="name" type="text"
                           id="name">
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="beta" id="beta">
                        <label class="custom-control-label" for="beta">Is Beta Version?</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file" id="file">
                        <label class="custom-file-label" for="file">Choose file</label>
                    </div>
                </div>
                <input class="btn btn-primary" type="submit" value="Submit">
            </form>
        </div>
    </div>
@endsection

@section('js')
    {!! $chart->script() !!}
@endsection
