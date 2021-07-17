@extends('emails.layouts.app')

@section('title', __('auth.emails.register.title'))

@section('content')
    <p>
        {{ __('auth.emails.register.a_new_user_registered'): }}
    </p>

    <p>
        {{ __('validation.attributes.name') }}: {{ $user->name }}<br>
        {{ __('validation.attributes.email') }}: {{ $user->email }}
    </p>

    <p>
        {{ __('auth.emails.register.click_here_to_activate_the_user') }}:
    </p>
@endsection
