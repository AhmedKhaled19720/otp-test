@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <!-- Tab navigation -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button"
                            role="tab" aria-controls="home" aria-selected="true">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button"
                            role="tab" aria-controls="profile" aria-selected="false">Login with Email</button>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content" id="myTabContent">
                    <!-- Regular Login Form -->
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card">
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="row mb-3">
                                        <label for="email"
                                            class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                        <div class="col-md-6">
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password"
                                            class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                        <div class="col-md-6">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                required autocomplete="current-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Login') }}
                                            </button>
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- OTP Login Form -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card">
                            <div class="card-body">
                                <!-- Email Form -->
                                <div id="error-div" class="alert alert-danger d-none">
                                    @if (session('error'))
                                        {{ session('error') }}
                                    @endif

                                    @if ($errors->any())
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>


                                <form id="emailForm">
                                    @csrf

                                    <div class="row mb-3" id="email-section">
                                        {{-- <div class="alert alert-danger d-none" role="alert"></div> --}}
                                        <label for="email"
                                            class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                        <div class="col-md-6">
                                            <input id="email-opt" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3 d-none" id="otp-section">
                                        <label for="otp"
                                            class="col-md-4 col-form-label text-md-end">{{ __('OTP Code') }}</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="otp" name="otp"
                                                required autofocus>
                                        </div>
                                    </div>

                                    <div class="row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="button" class="btn btn-primary" id="sendCodeBtn">
                                                {{ 'Send Code To Mail' }}
                                            </button>
                                            <button type="button" class="btn btn-primary mt-3 d-none"
                                                id="verifyOtpBtn">{{ __('Verify OTP') }}</button>

                                        </div>
                                    </div>
                                </form>
                                <div id="resend-otp" class="d-none">Didn’t receive the OTP?
                                    <form id="resendOtpForm" class="d-inline" method="POST"
                                        action="{{ route('otp.resend') }}">
                                        @csrf
                                        <input type="hidden" id="email-opt-hidden" name="email" value="">
                                        <button type="submit" class="btn btn-link" id="resendOtpBtn" disabled>
                                            Resend Code After <span id="timer">60</span> seconds
                                        </button>
                                    </form>



                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>

    <script>
        // send code to email btn 
        $(document).ready(function() {
            $('#sendCodeBtn').click(function() {
                var email = $('#email-opt').val();

                if (email) {
                    $.ajax({
                        url: '{{ route('otp.send') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            email: email
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#email-section').addClass('d-none');
                                $('#sendCodeBtn').addClass('d-none');
                                $('#otp-section').removeClass('d-none');
                                $('#resend-otp').removeClass('d-none');
                                $('#verifyOtpBtn').removeClass('d-none');
                                $('#timer').removeClass('d-none');
                            } else {
                                $('#error-div').html(response.message).removeClass('d-none');
                            }
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 422) { // Unprocessable Entity
                                let errors = xhr.responseJSON.errors;
                                let errorMessage = '';
                                $.each(errors, function(key, value) {
                                    errorMessage += value.join('<br>');
                                });
                                $('#error-div').html(errorMessage).removeClass('d-none');
                            } else {
                                let errorMessage = xhr.responseText ||
                                    'Failed to load this response: No content available.';
                                $('#error-div').html(errorMessage).removeClass('d-none');
                            }
                        }

                    });
                } else {
                    $('#error-div').html("Please Enter Your Email").removeClass('d-none');
                }
            });
        });



        // verify otp btn 
        $(document).ready(function() {
            startTimer();

            $('#verifyOtpBtn').click(function() {
                var otp = $('#otp').val();
                var email = $('#email-opt').val();

                if (otp && email) {
                    $.ajax({
                        url: '{{ route('otp.verify') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            otp: otp,
                            email: email
                        },
                        success: function(response) {
                            console.log('Response received:', response);
                            if (response.status === 'success') {
                                window.location.href = '{{ route('home') }}';
                            } else {
                                $('#otp-error').text(response.message).removeClass('d-none');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Status: " + status);
                            console.error("Error: " + error);
                            console.error("Response: " + xhr.responseText);
                            alert("An error occurred. Please try again.");
                        }
                    });
                } else {
                    alert('Please enter the OTP and email.');
                }
            });

        });


        // resend otp code to email 

        $('#resendOtpBtn').click(function(event) {
            event.preventDefault();

            $('#email-opt-hidden').val($('#email-opt').val());

            $.ajax({
                url: $('#resendOtpForm').attr('action'),
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    email: $('#email-opt-hidden').val()
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#email-section').addClass('d-none');
                        $('#sendCodeBtn').addClass('d-none');
                        $('#otp-section').removeClass('d-none');
                        $('#resend-otp').removeClass('d-none');
                        $('#verifyOtpBtn').removeClass('d-none');
                        $('#timer').removeClass('d-none');

                        startTimer();
                    } else {
                        $('#error-div').html(response.message).removeClass('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Status: " + status);
                    console.error("Error: " + error);
                    console.error("Response: " + xhr.responseText);
                    alert("An error occurred. Please try again.");
                }
            });
        });

        function startTimer() {
            var timer = $('#timer');
            var timeLeft = 60;
            timer.text(timeLeft);
            timer.removeClass('d-none');

            $('#resendOtpBtn').prop('disabled', true);

            var interval = setInterval(function() {
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    timer.text('0');
                    $('#resendOtpBtn').prop('disabled', false);
                } else {
                    timeLeft -= 1;
                    timer.text(timeLeft);
                }
            }, 1000);
        }
    </script>
@endsection
