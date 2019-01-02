@extends('emails.layouts.app')

@section('title', __('auth.emails.register.title'))

@section('content')
    <p>
        @lang('auth.emails.register.a_new_user_registered'):
    </p>

    <p>
        @lang('validation.attributes.name'): {{ $user->name }}<br>
        @lang('validation.attributes.email'): {{ $user->email }}
    </p>

    <p>
        @lang('auth.emails.register.click_here_to_activate_the_user'):
    </p>
@endsection