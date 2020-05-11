@extends('layouts.app')

@section('content')
    <h1>Create Download</h1>
    {!! Form::open(['action' => 'DownloadsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
        {{ Form::label('title', 'Title') }}
        {{ Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Title','required' => 'required']) }}
    </div>
    <div class="form-group">
        {{ Form::file( 'file' ) }}
    </div>
    {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
    <a href="/admin" class="btn btn-outline-dark">Cancel</a>
    {!! Form::close() !!}
@endsection
