<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function generate(Request $request)
    {
        // Validate request
        $data = $request->validate([
            'mobile' => 'required|string|size:11|starts_with:09',
            'reason' => 'sometimes|string|in:login,validation',
        ]);
        
        // Check if user exists with this mobile number
        $user = User::where('mobile', $data['mobile'])->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'کاربری با این شماره همراه یافت نشد'
            ], 404);
        }
        
        // Generate OTP
        $otpData = [
            'mobile' => $data['mobile'],
            'type' => 'mobile'
        ];
        
        $reason = isset($data['reason']) ? $data['reason'] : 'login';
        $otp = Otp::generate($otpData, 'mobile', $reason);

        // Send SMS
        $template = "کد تایید شما جهت ورود به سامانه [code]";
        $template = str_replace("[code]", $otp->code, $template);
        $originator = "50004900549";
        $destination = $data['mobile'];
        $content = urlencode($template);
        $password = "Kk123456@";
        $username = "kanoondke";
        
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        
        $response = file_get_contents("http://95.142.235.130/sms/url_send.html?originator=$originator&destination=$destination&content=$content&password=$password&username=$username", false, stream_context_create($arrContextOptions));
        $response = json_decode($response, true);
        
        return response()->json([
            'success' => true,
            'message' => 'کد تایید با موفقیت ارسال شد'
        ]);
    }
    
    public function validate_otp(Request $request)
    {
        $data = $request->validate([
            'mobile' => 'required|string|size:11|starts_with:09',
            'otp' => 'required|string|size:6',
            'reason' => 'sometimes|string|in:login,validation',
        ]);
        
        // Check if user exists
        $user = User::where('mobile', $data['mobile'])->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'کاربری با این شماره همراه یافت نشد'
            ], 404);
        }
        
        // Validate OTP
        $otpData = [
            'mobile' => $data['mobile'],
            'type' => 'mobile'
        ];
        
        $reason = isset($data['reason']) ? $data['reason'] : 'login';
        $result = Otp::validate($data['otp'], $otpData, $reason, 'mobile');
        
        if ($result == 1 && $reason == 'login') {
            // OTP is valid, login the user
            Auth::guard('backpack')->login($user);
            
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => 'ورود با موفقیت انجام شد'
            ]);
        } else if ($result == 2) {
            return response()->json([
                'success' => false,
                'message' => 'تعداد تلاش‌های شما بیش از حد مجاز است. لطفا دوباره کد تایید درخواست کنید.'
            ], 429);
        } else if ($result == 0) {
            return response()->json([
                'success' => false,
                'message' => 'کد تایید نامعتبر است'
            ], 400);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'خطا در تایید کد'
        ], 500);
    }
    
    
}
