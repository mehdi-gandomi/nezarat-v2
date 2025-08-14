@extends(backpack_view('layouts.plain'))

@section('content')
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-12 col-md-6 col-lg-4">
			<div class="my-4">
				<img style="width: 350px;margin: auto;display: block;" src="{{asset('/assets/images/logo-black.png')}}" />
			</div>
            <h3 class="text-center mb-4">{{ trans('backpack::base.login') }}</h3>
            
            <!-- Login Method Toggle Buttons -->
            <div class="row mb-3">
                <div class="col-6">
                    <button type="button" class="btn btn-primary w-100" id="username-login-btn" onclick="showUsernameLogin()">ورود با نام کاربری</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-primary w-100" id="otp-login-btn" onclick="showOtpLogin()">ورود با شماره همراه</button>
                </div>
            </div>
            
            <!-- Username/Password Login Form -->
            <div id="username-login-form">
                <div class="card p-2">
                    <div class="card-body">
                        <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label class="control-label" for="{{ $username }}">نام کاربری</label>

                                <div>
                                    <input type="text" class="form-control{{ $errors->has($username) ? ' is-invalid' : '' }}" name="{{ $username }}" value="{{ old($username) }}" id="{{ $username }}">

                                    @if ($errors->has($username))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first($username) }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="password">{{ trans('backpack::base.password') }}</label>

                                <div>
                                    <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password">

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> {{ trans('backpack::base.remember_me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-block btn-primary">
                                        {{ trans('backpack::base.login') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if (backpack_users_have_email() && backpack_email_column() == 'email' && config('backpack.base.setup_password_recovery_routes', true))
                    <div class="text-center"><a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div>
                @endif
                @if (config('backpack.base.registration_open'))
                    <div class="text-center"><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></div>
                @endif
            </div>
            
            <!-- OTP Login Form -->
            <div id="otp-login-form" style="display: none;">
                <!-- Step 1: Mobile Input -->
                <div id="otp-step-1">
                    <div class="card p-2">
                        <div class="card-body">
                            <h5 class="text-center mb-4">خوش آمدید!</h5>
                            <hr class="mb-4">
                            
                            <div class="form-group">
                                <label class="control-label">شماره همراه</label>
                                <div>
                                    <input type="text" class="form-control" id="mobile-input" placeholder="09xxxxxxxxx" maxlength="11">
                                    <div id="mobile-error" class="invalid-feedback" style="display: none;"></div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-0">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-block btn-primary" id="send-otp-btn" onclick="sendOtp()">ارسال</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: OTP Code Input -->
                <div id="otp-step-2" style="display: none;">
                    <div class="card p-2">
                        <div class="card-body">
                            <h5 class="text-center mb-4">کد تایید ارسال شد</h5>
                            <p class="text-center text-muted mb-4">کد 6 رقمی ارسال شده به شماره <span id="mobile-display"></span> را وارد کنید</p>
                            
                            <div class="form-group">
                                <label class="control-label">کد تایید</label>
                                <div class="d-flex justify-content-center gap-2 mb-3" style="direction: ltr;">
                                    <input type="text" class="form-control text-center otp-input" maxlength="1" data-index="0">
                                    <input type="text" class="form-control text-center otp-input" maxlength="1" data-index="1">
                                    <input type="text" class="form-control text-center otp-input" maxlength="1" data-index="2">
                                    <input type="text" class="form-control text-center otp-input" maxlength="1" data-index="3">
                                    <input type="text" class="form-control text-center otp-input" maxlength="1" data-index="4">
                                    <input type="text" class="form-control text-center otp-input" maxlength="1" data-index="5">
                                </div>
                                <div id="otp-error" class="invalid-feedback text-center" style="display: none;"></div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <div class="text-center">
                                    <span id="timer-text" class="text-muted">ارسال مجدد کد تا <span id="timer">02:00</span></span>
                                    <button type="button" class="btn btn-link" id="resend-btn" onclick="resendOtp()" style="display: none;">ارسال مجدد کد</button>
                                </div>
                            </div>
                            
                            <div class="form-group mb-0">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-block btn-primary" id="verify-otp-btn" onclick="verifyOtp()">تایید</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let timerInterval;
        let countdown = 120; // 2 minutes in seconds
        
        function showUsernameLogin() {
            document.getElementById('username-login-form').style.display = 'block';
            document.getElementById('otp-login-form').style.display = 'none';
            document.getElementById('username-login-btn').className = 'btn btn-primary w-100';
            document.getElementById('otp-login-btn').className = 'btn btn-outline-primary w-100';
        }
        
        function showOtpLogin() {
            document.getElementById('username-login-form').style.display = 'none';
            document.getElementById('otp-login-form').style.display = 'block';
            document.getElementById('username-login-btn').className = 'btn btn-outline-primary w-100';
            document.getElementById('otp-login-btn').className = 'btn btn-primary w-100';
            resetOtpForm();
        }
        
        function resetOtpForm() {
            document.getElementById('otp-step-1').style.display = 'block';
            document.getElementById('otp-step-2').style.display = 'none';
            document.getElementById('mobile-input').value = '';
            document.getElementById('mobile-error').style.display = 'none';
            document.getElementById('otp-error').style.display = 'none';
            clearInterval(timerInterval);
            countdown = 120;
            updateTimer();
        }
        
        function sendOtp() {
            const mobile = document.getElementById('mobile-input').value;
            
            if (!mobile || mobile.length !== 11 || !mobile.startsWith('09')) {
                showMobileError('لطفا شماره همراه معتبر وارد کنید');
                return;
            }
            
            // Show loading state
            const sendBtn = document.getElementById('send-otp-btn');
            const originalText = sendBtn.innerHTML;
            sendBtn.innerHTML = 'در حال ارسال...';
            sendBtn.disabled = true;
            
            // Send AJAX request
            fetch('/ajax/otp/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ mobile: mobile })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show step 2
                    document.getElementById('otp-step-1').style.display = 'none';
                    document.getElementById('otp-step-2').style.display = 'block';
                    document.getElementById('mobile-display').textContent = mobile;
                    
                    // Start timer
                    startTimer();
                    
                    // Focus first OTP input
                    document.querySelector('.otp-input').focus();
                } else {
                    showMobileError(data.message || 'خطا در ارسال کد تایید');
                }
            })
            .catch(error => {
                showMobileError('خطا در ارتباط با سرور');
            })
            .finally(() => {
                sendBtn.innerHTML = originalText;
                sendBtn.disabled = false;
            });
        }
        
        function verifyOtp() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpCode = Array.from(otpInputs).map(input => input.value).join('');
            
            if (otpCode.length !== 6) {
                showOtpError('لطفا کد 6 رقمی را کامل وارد کنید');
                return;
            }
            
            const mobile = document.getElementById('mobile-input').value;
            
            // Show loading state
            const verifyBtn = document.getElementById('verify-otp-btn');
            const originalText = verifyBtn.innerHTML;
            verifyBtn.innerHTML = 'در حال تایید...';
            verifyBtn.disabled = true;
            
            // Send AJAX request
            fetch('/ajax/otp/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    mobile: mobile,
                    otp: otpCode 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to dashboard
                    window.location.href = '{{ backpack_url("dashboard") }}';
                } else {
                    showOtpError(data.message || 'کد تایید نامعتبر است');
                }
            })
            .catch(error => {
                showOtpError('خطا در ارتباط با سرور');
            })
            .finally(() => {
                verifyBtn.innerHTML = originalText;
                verifyBtn.disabled = false;
            });
        }
        
        function startTimer() {
            countdown = 120;
            updateTimer();
            timerInterval = setInterval(() => {
                countdown--;
                updateTimer();
                if (countdown <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('timer-text').style.display = 'none';
                    document.getElementById('resend-btn').style.display = 'inline-block';
                }
            }, 1000);
        }
        
        function resendOtp() {
            // clear existing OTP inputs
            const otpInputs = document.querySelectorAll('.otp-input');
            otpInputs.forEach(i => i.value = '');
            // hide any previous error
            document.getElementById('otp-error').style.display = 'none';
            // reset timer UI
            clearInterval(timerInterval);
            document.getElementById('timer-text').style.display = 'inline';
            document.getElementById('resend-btn').style.display = 'none';
            countdown = 120;
            updateTimer();
            startTimer();
            // re-send the OTP
            sendOtp();
            // focus first otp box
            const firstOtp = document.querySelector('.otp-input');
            if (firstOtp) firstOtp.focus();
        }
        
        function updateTimer() {
            const minutes = Math.floor(countdown / 60);
            const seconds = countdown % 60;
            document.getElementById('timer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
        
        function showMobileError(message) {
            const errorDiv = document.getElementById('mobile-error');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
        
        function showOtpError(message) {
            const errorDiv = document.getElementById('otp-error');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
        
        // OTP input handling
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    if (e.target.value.length === 1) {
                        if (index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });
        });
    </script>
@endsection
