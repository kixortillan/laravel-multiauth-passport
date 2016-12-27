@extends('layouts.email')

@section('content')
<div>
    <h1>Welcome to {{ config('app.name') }}</h1>
    <p>You have successfully registered your account. Click the button below to verify your registration.</p>
    <div class="text-center">
        <a href="{{ $url }}" class="btn">Get Started</a>
    </div>
</div>
@endsection

