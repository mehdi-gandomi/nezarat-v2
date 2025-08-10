<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function generate(Request $request)
    {
        //request type : login,signup
        $requestType=$request->get("request_type","login");
       if($requestType == "login"){
            $data=$request->validate([
                'type'=>'required|in:email,mobile',
                'mobile'=>"bail|required_without:email|required_if:type,mobile",//
                'email'=>"bail|required_without:mobile|required_if:type,email|email",
                // "reason"=>'sometimes|in:reset_password,verification'
            ]);
       }else{
            $data=$request->validate([
                'type'=>'required|in:email,mobile',
                'mobile'=>"bail|required_without:email|required_if:type,mobile",//|iran_mobile
                'email'=>"bail|required_without:mobile|required_if:type,email|email",
                "reason"=>'sometimes|in:reset_password,verification'
            ]);
       }
       
        
            $reason=isset($data['reason']) ? $data['reason']:"verification";
            $otp=Otp::generate($data,$data['type'],$reason);

            $template="کد تایید شما جهت ورود به سامانه شکایات مردمی دفاتر خدمات الکترونیک قضایی [code]";
            $template=str_replace("[code]",$otp->code,$template);
            $originator="50004900549";
            $destination=$data['mobile'];
            $content=urlencode($template);
            $password="Kk123456@";
            $username="kanoondke";
            $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );
    $response=file_get_contents("http://95.142.235.130/sms/url_send.html?originator=$originator&destination=$destination&content=$content&password=$password&username=$username", false, stream_context_create($arrContextOptions));
		$response=json_decode($response,true);
        
        return response()->json([
            'ok'=>true,
            
            'message'=>trans("Otp::auth.otp_sent_message")
        ]);
        // return success_response_without_data(trans("auth.otp_sent"));
    }
    public function validate_otp(Request $request)
    {

        $data=$request->validate([
            'code'=>'required',
            'type'=>'required|in:email,mobile',
            'mobile'=>"sometimes|required_if:type,mobile",//
            'email'=>"sometimes|required_if:type,email|email",
            // "reason"=>'sometimes|in:reset_password,verification'
        ]);
        $reason=isset($data['reason']) ? $data['reason']:"verification";
        $result=Otp::validate($data['code'],$data,$reason,$data['type']);

        // $result=Otp::validate_otp($data['mobile'],$data['otp']);
        if($result == 1){
            // if(request('request_type') == 'login'){

            //     $user=User::where("mobile",$request->mobile)->first();

            //     auth()->login($user);

            //     if ($request->hasSession()) {
            //         $request->session()->put('auth.password_confirmed_at', time());
            //     }
            //     $request->session()->regenerate();
            // }
            return response()->json([
                'ok'=>true,
                'message'=>trans("auth.otp_true")
            ]);
        }else if($result == 2){
            return response()->json([
                'ok'=>false,
                'message'=>trans("auth.otp_max_attempts")
            ]);
        }else if($result == 0){
            return response()->json([
                'ok'=>false,
                'message'=>trans("auth.otp_not_found")
            ]);
        }
    }
}
