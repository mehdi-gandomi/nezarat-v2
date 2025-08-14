<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تست OTP API</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            direction: rtl;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>تست OTP API</h1>
    
    <div class="form-group">
        <label for="mobile">شماره همراه:</label>
        <input type="text" id="mobile" placeholder="09123456789" maxlength="11">
    </div>
    
    <button onclick="sendOtp()" id="sendBtn">ارسال کد تایید</button>
    
    <div id="otpSection" style="display: none;">
        <div class="form-group">
            <label for="otp">کد تایید:</label>
            <input type="text" id="otp" placeholder="1234" maxlength="4">
        </div>
        <button onclick="verifyOtp()" id="verifyBtn">تایید کد</button>
    </div>
    
    <div id="result"></div>

    <script>
        function sendOtp() {
            const mobile = document.getElementById('mobile').value;
            const sendBtn = document.getElementById('sendBtn');
            
            if (!mobile || mobile.length !== 11 || !mobile.startsWith('09')) {
                showResult('لطفا شماره همراه معتبر وارد کنید', 'error');
                return;
            }
            
            sendBtn.disabled = true;
            sendBtn.textContent = 'در حال ارسال...';
            
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
                    showResult('کد تایید با موفقیت ارسال شد', 'success');
                    document.getElementById('otpSection').style.display = 'block';
                } else {
                    showResult(data.message || 'خطا در ارسال کد تایید', 'error');
                }
            })
            .catch(error => {
                showResult('خطا در ارتباط با سرور', 'error');
            })
            .finally(() => {
                sendBtn.disabled = false;
                sendBtn.textContent = 'ارسال کد تایید';
            });
        }
        
        function verifyOtp() {
            const mobile = document.getElementById('mobile').value;
            const otp = document.getElementById('otp').value;
            const verifyBtn = document.getElementById('verifyBtn');
            
            if (!otp || otp.length !== 4) {
                showResult('لطفا کد 4 رقمی را کامل وارد کنید', 'error');
                return;
            }
            
            verifyBtn.disabled = true;
            verifyBtn.textContent = 'در حال تایید...';
            
            fetch('/ajax/otp/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    mobile: mobile,
                    otp: otp 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showResult('کد تایید صحیح است! ورود با موفقیت انجام شد.', 'success');
                } else {
                    showResult(data.message || 'کد تایید نامعتبر است', 'error');
                }
            })
            .catch(error => {
                showResult('خطا در ارتباط با سرور', 'error');
            })
            .finally(() => {
                verifyBtn.disabled = false;
                verifyBtn.textContent = 'تایید کد';
            });
        }
        
        function showResult(message, type) {
            const resultDiv = document.getElementById('result');
            resultDiv.textContent = message;
            resultDiv.className = 'result ' + type;
        }
    </script>
</body>
</html>
