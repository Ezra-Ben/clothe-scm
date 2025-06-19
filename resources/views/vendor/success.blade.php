@extends('components.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <div class="container">
        <div class="success-message">
           <strong>{{ $message }}</strong>
           <br><br>
            <a href="{{ route('dashboard') }}" class="btn-primary">Go to Dashboard</a>
        </div>
    </div>
@endsection