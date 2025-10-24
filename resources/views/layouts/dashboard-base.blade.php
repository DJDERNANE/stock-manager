@extends('layouts.base-dashboard')
@section('title', isset($title) ? $title . ' - ' . config('app.name') : config('app.name'))
@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.dashboard-sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            @yield('dashboard-content')
        </main>
    </div>