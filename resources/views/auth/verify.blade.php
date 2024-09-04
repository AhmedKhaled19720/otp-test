@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify Your OTP') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{ __('Please enter the OTP sent to your email.') }}

                        <form method="POST" action="{{ route('otp.verify') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">{{ __('Email Address') }}</label>
                                <input type="hidden" name="email" value="{{ session('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="otp">{{ __('OTP Code') }}</label>
                                <input type="text" class="form-control" id="otp" name="otp" required autofocus>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">{{ __('Verify OTP') }}</button>
                        </form>

                        <hr>

                        {{-- <div>
                            {{ __('If you did not receive the OTP') }},
                            <form class="d-inline" method="POST" action="{{ route('otp.resend') }}">
                                @csrf
                                <button type="submit"
                                    class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                            </form>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
