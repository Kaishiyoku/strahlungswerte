@extends('layouts.app')

@section('title', __('passwords.form.title'))

@section('content')
    <div class="row justify-content-md-center">
        <div class="col col-lg-8">
            <div class="card border-primary">
                <h4 class="card-header text-white bg-primary">
                    @lang('passwords.form.title')
                </h4>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ Form::open(['url' => '/password/email', 'method' => 'post', 'role' => 'form']) }}
                        <div class="form-group row">
                            {{ Form::label('email', __('validation.attributes.email'), ['class' => 'col-lg-4 col-form-label']) }}

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
                            <div class="col-lg-8 ml-md-auto">
                                {{ Form::button(__('passwords.form.submit'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
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
           $('#email').focus();
        });
    </script>
@endsection