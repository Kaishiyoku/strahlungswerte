@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap justify-center">
        <div class="w-full max-w-sm">
            <div class="flex flex-col break-words rounded overflow-hidden shadow-lg border border-gray-200 bg-white mt-8">
                <form class="w-full p-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="label">
                            {{ __('validation.attributes.email') }}:
                        </label>

                        <input id="email" type="email" class="input @error('email') has-error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('validation.attributes.email') }}" autofocus>

                        @error('email')
                            <p class="invalid-feedback">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="label">
                            {{ __('validation.attributes.password') }}
                        </label>

                        <input id="password" type="password" class="input @error('password') has-error @enderror" name="password" required placeholder="{{ __('validation.attributes.password') }}">

                        @error('password')
                            <p class="invalid-feedback">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="remember" class="label-checkbox">
                            <input type="checkbox" name="remember" id="remember" class="checkbox" {{ old('remember') ? 'checked' : '' }}>
                            <span>{{ __('login.remember_me') }}</span>
                        </label>
                    </div>

                    <div class="flex flex-wrap items-center">
                        <button type="submit" class="btn btn-primary">
                            {{ __('login.submit') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-purple-500 hover:text-purple-700 whitespace-no-wrap no-underline ml-auto" href="{{ route('password.request') }}">
                                {{ __('login.forgot_password') }}
                            </a>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
