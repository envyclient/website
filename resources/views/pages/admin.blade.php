@extends('layouts.dash')

@section("content")
    <div class="tab-content" style="width:95%;margin:0 auto">

        <!-- users -->
        <div class="tab-pane fade custom-panel show active" id="users" role="tabpanel">

            <!-- users chart -->
            <div>
                <div class="alert alert-primary" style="font-size:25px;">
                    Stats
                </div>
                <div style="height: 300px">
                    {!! $usersChart->container() !!}
                </div>
            </div>

            <br>

            <!-- users table -->
            <div id="users-table">
                <div class="alert alert-secondary" style="font-size:25px;">
                    User Management
                </div>
                <users-table url="{{ route('api.admin.users') }}"
                             api-token="{{ $apiToken }}">
                </users-table>
            </div>
        </div>

        <!-- versions -->
        <div class="tab-pane fade custom-panel" id="versions" role="tabpanel">

            <!-- versions table -->
            <div>
                <div class="alert alert-primary" style="font-size:25px;">
                    Versions
                </div>
                <div style="height: 300px">
                    {!! $versionsChart->container() !!}
                </div>

                <versions-table url="{{ route('api.versions.index') }}" api-token="{{ $apiToken }}"></versions-table>
            </div>

            <br>

            <!-- create version -->
            <div>
                <div class="alert alert-secondary" style="font-size:25px;">
                    Create Version
                </div>

                {!! Form::open(['action' => 'VersionsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name', 'required' => 'required']) }}
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
                {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                {!! Form::close() !!}
            </div>
        </div>

        <!-- game sessions -->
        <div class="tab-pane fade custom-panel" id="game-sessions" role="tabpanel">
            <div class="alert alert-primary" style="font-size:25px;">
                Game Sessions
            </div>
            <div class="row">
                <div class="col" style="height: 700px">
                    {!! $gameSessionsChart->container() !!}
                </div>
                <div class="col" style="height: 700px">
                    {!! $gameSessionsToggleChart->container() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {!! $usersChart->script() !!}
    {!! $versionsChart->script() !!}
    {!! $gameSessionsChart->script() !!}
    {!! $gameSessionsToggleChart->script() !!}
@endsection
