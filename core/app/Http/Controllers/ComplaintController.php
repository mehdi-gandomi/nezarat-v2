<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;


class ComplaintController extends Controller
{
  
  public function show_form(Request $request){
	  $offices=User::whereNotNull('office_code')->get();
	  return view('front.complaint',compact('offices'));
  }
  
  public function store(Request $request){
	 $data=$request->validate([
	     'first_name'=>'required',
	     'last_name'=>'required',
	     'mobile'=>'required',
	     'national_code'=>'required',
	     'birth_date'=>'sometimes',
	     'office_code'=>'required',
	     'subject'=>'required',
	     'message'=>'required',
	     'hide_my_name'=>'sometimes'
	 ]);
	 $complaint=\App\Models\Complaint::create($data);
	 return back()->with('success','شکایت شما ثبت شد');
  }
     

}
