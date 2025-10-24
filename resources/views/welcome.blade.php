@extends('layouts.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h3 mb-0">Welcome to {{ config('app.name') }}</h1>
                </div>
                <div class="card-body">
                    <p class="lead">This is your home page content.</p>
                    <button class="btn btn-primary">Get Started</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection