@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap justify-center">
        <div class="w-full max-w-sm">
            <div class="flex flex-col mt-8 card">
                <form class="w-full p-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <x-label for="email" :value="__('validation.attributes.email')" required/>

                        <x-input id="email" class="{{ classNames('block mt-1 w-full', ['has-error' => $errors->has('email')]) }}" type="email" name="email" :value="old('email')" :placeholder="__('validation.attributes.email')" required autocomplete="email" autofocus/>

                        @error('email')
                            <p class="invalid-feedback">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-label for="password" :value="__('validation.attributes.password')" required/>

                        <x-input id="password" class="{{ classNames('block mt-1 w-full', ['has-error' => $errors->has('password')]) }}" type="password" name="password" :placeholder="__('validation.attributes.password')" required/>

                        @error('password')
                            <p class="invalid-feedback">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="block mt-4">
                        <label for="remember_me" class="flex items-center">
                            <x-checkbox id="remember_me" name="remember" />
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-500">{{ __('login.remember_me') }}</span>
                        </label>
                    </div>

                    <div class="flex flex-wrap items-center mt-4">
                        <x-button>
                            {{ __('login.submit') }}
                        </x-button>

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
