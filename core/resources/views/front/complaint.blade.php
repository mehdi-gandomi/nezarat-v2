<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فرم شکایت</title>
    <link href="/assets/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="/assets/css/fontiran.css" rel="stylesheet">
    <link href="/assets/css/select2.min.css" rel="stylesheet">
         <link href="/assets/css/persian-datepicker.min.css" rel="stylesheet">
    <style>
        .select2{
            height: 40px !important;
        }
        .selection,.select2-selection,.select2-selection__arrow{
            height: 100% !important;
        }
        .select2-selection__rendered{
            height: 100% !important;
            line-height: 40px !important;
        }
        .office-code-hint{
            text-decoration:none;
            font-size:13px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>فرم شکایت</h2>
        
        <form class="row" id="complaint-form">
            <div class="col-lg-12">
                <img style="width: 350px;margin: auto;display: block;" src="/assets/images/logo-black.png">
            </div>
            <div class="mb-3 col-lg-4">
                <label for="first_name" class="form-label">نام</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="mb-3 col-lg-4">
                <label for="last_name" class="form-label">نام خانوادگی</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="mb-3 col-lg-4">
                <label for="mobile" class="form-label">تلفن همراه</label>
                <input type="text" class="form-control" id="mobile" name="mobile" maxlength="11" required>
            </div>
            <div class="mb-3 col-lg-4">
                <label for="national_code" class="form-label">کد ملی</label>
                <input type="text" class="form-control" id="national_code" name="national_code" maxlength="10" required>
            </div>
            <div class="mb-3 col-lg-4">
                <label for="birth_date" class="form-label">تاریخ تولد</label>
                <input type="text" class="form-control" id="birth_date" name="birth_date" required>
            </div>
            <div class="mb-3 col-lg-4">
                <label for="office_code" class="form-label">کد دفتر  <a class="office-code-hint" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">کد دفتر را نمی دانید؟</a></label>
                
                <select class="form-control" id="office_code" name="office_code" required>
                    @foreach($offices as $office)
                        <option value="{{$office->office_code}}">{{$office->office_code}}</option>
                    @endforeach
                </select>
            </div>
   
            <div class="mb-3 col-lg-4">
                <label for="subject" class="form-label">موضوع</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="mb-3 col-lg-12">
                <label for="message" class="form-label">پیام</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
                     <div class="mb-3 form-check col-lg-12">
                <input type="checkbox" class="form-check-input" id="hide_my_name" name="hide_my_name">
                <label class="form-check-label" for="hide_my_name">عدم نمایش نام</label>
            </div>
            <button type="submit" class="btn btn-primary">ارسال</button>
        </form>
    </div>

    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">توضیحات</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        توضیحات میاد اینجا
      </div>
   
    </div>
  </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ورود / ثبت نام</h5>
                    <div style="margin-left: unset !important;cursor:pointer" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="loginForm"  method="post">
                        @csrf
                        <section id="otpValidation">
                            <div class="row d-none" id="otp-step-1">
                               <div class="col-lg-6 offset-lg-3">
                                   <div class="form-group mb-4">
                                       <label for="tel">{{__('شماره همراه')}} *</label>
                                       <input oninput="checkPhoneExistance(this)" style="direction: ltr" class="form-control phone-input" type="text" id="otp-phone-input" name="username" value="{{old('username')}}" placeholder="{{__('Enter_phone_number')}}" required>
                                   </div>
                                   <button id="otp-send-code" type="button" onclick="sendCode()" class="btn btn-primary">
                                       تایید
                                   </button>
                               </div>
                           </div>
                           <div class="row " id="otp-step-2">
                               <div class="col-lg-6 offset-lg-3">
                                   <div class="form-group mb-4">
                                       <label for="tel">{{__('کد')}} *</label>
                                       <div style="direction:ltr" id="otp_target"></div>
                                   </div>
                                   <div class="text-left mb-2">
                                   {{__('زمان باقیمانده تا ارسال مجدد کد')}}: <span id="timer"></span>
                                   </div>
                                   <div class="d-flex">
                                       <button id="otp-verify-code" type="button" onclick="verifyCode()" class="btn btn-primary">
                                       {{__('تایید کد')}}
                                   </button>
                                   <button id="otp-resend-code" type="button" onclick="resendCode()" class="btn btn-primary">
                                       {{__('ارسال مجدد کد')}}
                                   </button>
                                   </div>
                               </div>
                           </div>
                           
                       </section>
                       
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/select2.min.js"></script>
      <script src="/assets/js/persian-date.min.js"></script>  
      <script src="/assets/js/persian-datepicker.min.js"></script>
      
    <script src="{{ asset('assets/js/otpdesigner.min.js') }}"></script>
    
    <script>
    window.phone_validated=0;
    $("#complaint-form").on("submit",function(e){
        e.preventDefault();
        if(!window.phone_validated) {
            sendCode()
            $("#authModal").modal('show')
        }
        
        console.log($(this).serialize())
    })
        $("#office_code").select2()
         if($("#birth_date").length){
                    $("#birth_date").attr("type","text")
                    //$("#from_date").attr("name","from_display")
                    $("#birth_date").removeAttr("name")
                    $("#birth_date").after( `<input type="hidden" id="birth_date_alt" name="birth_date" />` );
                    $("#birth_date").pDatepicker({
                            autoClose: true,
                            format:"YYYY/MM/DD",
                            altField:"#birth_date_alt",
                            altFieldFormatter:(unixDate)=>{
                                const d=new Date(unixDate)
                                return `${d.getFullYear()}-${("0" + (d.getMonth()+1)).slice(-2) }-${d.getDate()}`
                            }
                    });
                }
                    
    </script>
    
<script>
            function loginWithPassword(){
            $("#loginForm").submit()
        }
        function loginWithOtp(){

        }
        
        window.otpCode="";
        window.otpValidated=false;
        let timerOn = true;
        window.phoneExists=null;
        $(".phone-input").on("input", e => {
            console.log(e)
            $("#otp-send-code").prop("disabled", true)
            if (!e.target.value) return;
            window.phoneExists = false;
            if(isPhone(e.target.value)) {
                $.get({
                url: "{{ url('/') }}/ajax/check-existance?" + $.param({
                    field: "mobile",
                    value: e.target.value
                }),
                dataType: 'json',
                error() {
                    $("#otp-send-code").prop("disabled", false)
                },
                success: function(data) {
                    console.log(data)
                    $(e.target).parent().find(".error").remove()
                    $("#otp-send-code").prop("disabled", false)
                    window.phoneExists=data.data.exists;
                    //if exists show password if not send code
                    // if (data.data.exists) {
                    //     window.phoneExists = true;
                    //     $(`<label id="${e.target.getAttribute('id')}-error" class="error" for="${e.target.getAttribute('id')}">{{ __('This phone is used by another account') }}</label>`)
                    //         .insertBefore(e.target)
                    // } else {
                    //     $("#otp-send-code").prop("disabled", false)
                    // }
                },
            });
            }
            return;
            
        })
    
        function checkPhoneExistance(el){
        const value=el.value.replace(/\-/g,"")
         $("#otp-send-code").prop("disabled", true)
            if (value || value.length < 11) return;
            window.phoneExists = false;
            $.get({
                url: "{{ url('/') }}/shop/check-existance?" + $.param({
                    field: "phone",
                    value: value
                }),
                dataType: 'json',
                error() {
                    $("#otp-send-code").prop("disabled", false)
                },
                success: function(data) {
                    console.log(data)
                    $(e.target).parent().find(".error").remove()
                    if (data.data.exists) {
                        window.phoneExists = true;
                        $(`<label id="${e.target.getAttribute('id')}-error" class="error" for="${e.target.getAttribute('id')}">{{ __('This phone is used by another account') }}</label>`)
                            .insertBefore(e.target)
                    } else {
                        $("#otp-send-code").prop("disabled", false)
                    }
                },
            });

    }
        function isPhone(mobile)    {
            var regex = new RegExp(/^(0|0098|\+98|)9(0[1-5]|[1 3]\d|2[0-2]|98)\d{7}$/i);
            return regex.test(mobile);
        }
    function timer(remaining) {
          var m = Math.floor(remaining / 60);
          var s = remaining % 60;

          m = m < 10 ? '0' + m : m;
          s = s < 10 ? '0' + s : s;
          document.getElementById('timer').innerHTML = m + ':' + s;
          remaining -= 1;

          if(remaining >= 0 && timerOn) {
            setTimeout(function() {
                timer(remaining);
            }, 1000);
            return;
          }

          if(!timerOn) {
            // Do validate stuff here
            $("#otp-resend-code").prop("disabled",false)
            return;
          }

          $("#otp-resend-code").prop("disabled",false)
        }
        function sendCode(){
         
         
            $("#otp-send-code").prop("disabled",true)
            fetch("/ajax/otp/send", {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({type:"mobile",mobile:$("#mobile").val(),_token:"{{csrf_token()}}"})
          }).then(res=>res.json())
            .then(res=>{
                $("#otp-send-code").prop("disabled",false)
                console.log(res)
                if(res.token == 'active'){

                    $("#otp-step-1").addClass("d-none")
                    $("#otp-step-2").removeClass("d-none")
                   setTimeout(()=>{
                     window.otpInput= $('#otp_target').otpdesigner({
                        length: 4,
                                typingDone: function (code) {
                                    window.otpCode=code
                                },
                            });
        $("#otp-resend-code").prop("disabled",true)

        timer(120);

                   },300)
                }else{
                    if(res.errors && res.errors.length){
                        toastr.error(res.errors[0].message);
                    }
                }


            })
            .catch(e=>{
                $("#otp-send-code").prop("disabled",false)
            })
        }

        function verifyCode(){
            $("#otp-verify-code").prop("disabled",true)
            fetch('/ajax/otp/validate', {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({type:"mobile",mobile:$("#mobile").val(),_token:"{{csrf_token()}}",otp:otpCode})
          }).then(res=>res.json())
            .then(res=>{
                console.log(res)
                $("#otp-verify-code").prop("disabled",false)
               if(res.ok){
                $("#tel").val($("#otp-phone-input").val())
                window.otpValidated=true;
                $("#otpValidation").addClass("d-none")
                $("#lastStep").removeClass("d-none")
                $("#phone").val($("#otp-phone-input").val())
                $("#complaint-form").submit()
               }else if(res.errors){
                window.otpValidated=false;
                alert(res.errors[0].message)
               }


            })
            .catch(e=>{
                $("#otp-verify-code").prop("disabled",false)
                if(e.response){
                    if(e.response.data.message){
                        alert(e.response.data.message)
                    }
                }
            })
        }
        function resendCode(){
            $("#otp-resend-code").prop("disabled",true)
            fetch('', {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({phone:$("#otp-phone-input").val(),_token:$("#csrf-token-meta").attr('content')})
          }).then(res=>res.json())
            .then(res=>{
                console.log(res)
                timer(120);
               if(res.message){

               }else if(res.errors){

                alert(res.errors[0].message)
               }


            })
            .catch(e=>{
                $("#otp-resend-code").prop("disabled",false)
                if(e.response){
                    if(e.response.data.message){
                        alert(e.response.data.message)
                    }
                }
            })
        }
        </script>
</body>
</html>
