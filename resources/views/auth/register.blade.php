@extends('layouts.app')

@section('title', __('register.title'))

@section('content')
    <div class="row justify-content-md-center">
        <div class="col col-lg-8">
            <div class="card border-primary">
                <h4 class="card-header text-white bg-primary">
                    @lang('register.title')
                </h4>
                <div class="card-body">
                    {{ Form::open(['route' => 'register', 'method' => 'post', 'role' => 'form']) }}
                        @captcha()

                        <div class="form-group row">
                            {{ Form::label('name', __('register.name'), ['class' => 'col-lg-4 col-form-label']) }}

                            <div class="col-lg-6">
                                {{ Form::text('name', old('name'), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'required' => 'true']) }}

                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label('email', __('register.email'), ['class' => 'col-lg-4 col-form-label']) }}

                            <div class="col-lg-6">
                                {{ Form::email('email', old('email'), ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'required' => true]) }}

                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label('password', __('register.password'), ['class' => 'col-lg-4 col-form-label']) }}

                            <div class="col-lg-6">
                                {{ Form::password('password', ['class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''), 'required' => true, 'data-provide' => 'password-strength', 'data-username-field' => '#name']) }}

                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label('password_confirmation', __('register.password_confirmation'), ['class' => 'col-lg-4 col-form-label']) }}

                            <div class="col-lg-6">
                                {{ Form::password('password_confirmation', ['class' => 'form-control' . ($errors->has('password_confirmation') ? ' is-invalid' : ''), 'required' => true]) }}

                                @if ($errors->has('password_confirmation'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password_confirmation') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-8 ml-md-auto">
                                {{ Form::button(__('register.submit'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#name').focus();
        });
    </script>
@endsection