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

                    You are logged in!

                    <div class="links">
                        <br>
                        <a href="{{ url('/download') }}">Download an existing excel sheet</a>
                        <br>
                        <a href="{{ url('/create') }}">Create a new excel sheet</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection