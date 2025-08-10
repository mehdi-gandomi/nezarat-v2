<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table='otp';
    use HasFactory;
    public $guarded=[];
    public static function generate($data,$type,$reason="verification")
    {
        $check_otp=Otp::where($type,$data[$type])->where("reason",$reason)->where("created_at",">=",Carbon::now()->subMinutes(2))->first();
        if($check_otp){
            $check_otp->delete();
        }
        Otp::where($type,$data[$type])->where("created_at","<",Carbon::now()->subMinutes(2))->delete();
        $otp = rand(111111,999999);
        if(isset($data['type'])){
            unset($data['type']);
        }

        $data['ip']=request()->getClientIp();
        $data['user_agent']=request()->header('User-Agent');
        $data['code']=$otp;
        $data['reason']=$reason;
        $data['status']="PENDING";
        $otp=Otp::create($data);

        return $otp;
        // while($this->exists()){
        //     $otp = rand(100000, 999999);
        // }

    }
    public static function generateForUser($user,$reason="verification")
    {
        $check_otp=Otp::where("user_id",$user->getKey())->where("reason",$reason)->where("created_at",">=",Carbon::now()->subMinutes(10))->first();
        if($check_otp){
            return $check_otp;
        }else{
            Otp::where("user_id",$user->getKey())->where("created_at","<",Carbon::now()->subMinutes(10))->delete();
            $otp = rand(111111,999999);
            $data['ip']=request()->getClientIp();
            $data['user_agent']=request()->header('User-Agent');
            $data['code']=$otp;
            $data['user_id']=$user->getKey();
            $data['reason']=$reason;
            $data['status']="PENDING";
            $otp=Otp::create($data);
        }
        return $otp;
    }
    public static function validate($otp,$data,$reason="verification",$login_type="mobile"){
        $check_otp = self::where($login_type,$data[$login_type])
            // ->where("reason",$reason)
            ->where(function($query){
                $query->where('created_at','>=',Carbon::now()->addMinutes('-2'));
                $query->whereStatus("PENDING");
                // $query->where('attemtps','<=',config("Otp.max_attempts"));
            })->orderby("created_at","desc")->orderby("updated_at","desc")->first();
        if($check_otp){
            $check_otp->increment("attempts");
            if($check_otp->attempts >= 5){
                $check_otp->delete();
                return 2; // max attempts exceeded so cannot let user login
            }
            else if($check_otp->code == $otp && $check_otp->attempts < 5){
                $check_otp->delete();
                return 1; // if attempts is lesser that max attempts so its good
            }
            return 0;//none of the conditions is true then we cannot let user login
        }

        return 0;//otp not found so we cannot let user login
    }
    public static function validateForUser($otp,$user,$reason="verification"){

        $check_otp = self::where("user_id",$user->getKey())
            ->where("reason",$reason)
            ->where(function($query){
                $query->where('created_at','>=',Carbon::now()->addMinutes('-'.config("Otp.validation_minutes")));
                $query->whereStatus("PENDING");
                // $query->where('attemtps','<=',config("Otp.max_attempts"));
            })->orderby("created_at","desc")->orderby("updated_at","desc")->first();
        if($check_otp){
            $check_otp->increment("attempts");
            if($check_otp->attempts >= config("Otp.max_attempts")){
                $check_otp->delete();
                return 2; // max attempts exceeded so cannot let user login
            }
            else if($check_otp->code == $otp && $check_otp->attempts < config("Otp.max_attempts")){
                $check_otp->delete();
                return 1; // if attempts is lesser that max attempts so its good
            }
            return 0;//none of the conditions is true then we cannot let user login
        }

        return 0;//otp not found so we cannot let user login
    }
}
