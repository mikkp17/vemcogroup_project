@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    You are logged in!<br>
                    Excel sheets available for download:<br>

                    <div>
                        @foreach ($files as $file)
                        <a href="{{ url('/download/' . $file) }}">{{$file}}</a>
                        <br>
                        @endforeach
                    </div>

                    <div class="links">
                        <br>
                        <a href="{{ url('/create') }}">Create a new random report and store it on server</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection