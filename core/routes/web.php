<?php

use Illuminate\Support\Facades\Route;
use App\Models\City;
use App\Models\OfficeFile;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::post('admin/login', '\App\Http\Controllers\Admin\Auth\LoginController@login');
Route::get('generate', function (){
	// $count=\App\Models\InspectionLog::whereHas("office",function($q){
	// 	return $q->where("province_id",1);
	// })->whereHas("checklists.questions",function($q){
	// 	return $q->where("general_question_id",30)->where("rating",1);
	// })->count();
	$count=\App\Models\InspectionLog::whereHas("checklists.questions",function($q){
		return $q->where("general_question_id",30)->where("rating",1);
	})->count();
	return $count;
	dd(\App\Models\InspectionLog::first()->inspection_date_fa);
	return;
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
});

Route::get('ajax/cities', function (){
	$provinces=request('province_id');
	$provinces=$provinces ? explode(",",$provinces):[];
	return City::whereIn("province_id",$provinces)->get();
})->name("cities");
Route::post('ajax/otp/send', '\App\Http\Controllers\OtpController@generate');
Route::post('ajax/otp/validate', '\App\Http\Controllers\OtpController@validate_otp');
Route::get('ajax/offices', function (){
	if(!request('province_id')) return [
		'ok'=>false,
		'data'=>[]
	];
	$offices=OfficeFile::where("province_id",request('province_id'))->select('first_name','last_name','office_code')->get();

	return [
		'ok'=>true,
		'data'=>$offices
	];
});

Route::get('/', function () {
    return redirect('/admin/login');
});

// Test route for OTP API (remove in production)
Route::get('/test-otp', function () {
    return view('test-otp');
});

Route::get('/complaint','\App\Http\Controllers\ComplaintController@show_form');
Route::post('/complaint','\App\Http\Controllers\ComplaintController@store');
