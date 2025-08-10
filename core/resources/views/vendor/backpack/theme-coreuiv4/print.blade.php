@php
$employees=$inspectionLog->employees;
$officeEmployees = json_decode($inspectionLog->office->employees, true);
@endphp
@extends(backpack_view('blank'))

@section('after_styles')
    <style media="screen">
        .backpack-profile-form .required::after {
            content: ' *';
            color: red;
        }
    </style>
@endsection

@php
  $breadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      trans('backpack::base.my_account') => false,
  ];
@endphp


@section('header')
    <div class="container-fluid d-flex justify-content-between my-3">
        <section class="header-operation animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
            <h1 class="text-capitalize mb-0" bp-section="page-heading">{{ trans('Print') }}</h1>
            
            
        </section>
        <a href="javascript: window.print();" class="btn float-end float-right d-print-none"><i class="la la-print"></i></a>
    </div>
@endsection
    

@section('content')
   <section class="content-header">
        <div class="container-fluid mb-3">
            
			<div class="container-fluid animated fadeIn">


    <div class="row" bp-section="crud-operation-show">
        <div class="col-md-12">


            <div class="">
                <div class="card no-padding no-border mb-0">
                    <table class="table table-striped m-0 p-0">
                        <tbody>
                            <tr>
                                <td class="border-top-0">
                                    <strong>نام مدیر دفتر:</strong>
                                </td>
                                <td class="border-top-0">
                                    <span>
                                        {{$inspectionLog->office->first_name}} {{$inspectionLog->office->last_name}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-top-0">
                                    <strong>تصویر مدیر دفتر:</strong>
                                </td>
                                <td class="border-top-0">
                                    <span>
                                        <img style="width:100px"
                                            src="{{db_asset($inspectionLog->office->personel_image)}}">
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped m-0 p-0">
                        <tbody>
                            <tr>
                                <td class="border-top-0">
                                    <strong>کد دفتر:</strong>
                                </td>
                                <td class="border-top-0">
                                    <span>
                                        {{$inspectionLog->office_code}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>تطابق اطلاعات:</strong>
                                </td>
                                <td>
                                    {{$inspectionLog->adapt == 1 ? 'بله':'خیر'}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>نام بازرس:</strong>
                                </td>
                                <td>
                                    <span>


                                        <span class="d-inline-flex">
                                            {{$inspectionLog->user->name}}

                                        </span>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>دوره بازرسی:</strong>
                                </td>
                                <td>
                                    <span>
                                        {{$inspectionLog->inspection_period ?? "-"}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>تاریخ بازرسی:</strong>
                                </td>
                                <td>
                                    <span>
                                        {{$inspectionLog->inspection_date_fa ?? "-"}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>نیاز به بازرسی مجدد:</strong>
                                </td>
                                <td>
                                   {{$inspectionLog->requires_second_inspection == 1 ? "دارد":"ندارد"}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>تاریخ بازرسی مجدد:</strong>
                                </td>
                                <td>
                                    <span>
                                        {{$inspectionLog->second_inspection_date_fa ?? "-"}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>امضا بازرس:</strong>
                                </td>
                                <td>
                                    <span>
                                        @if($inspectionLog->inspector_signature)
											<img style="width:200px;height:200px" src="{{asset("storage/".$inspectionLog->inspector_signature)}}" />
										@else
											-
										@endif
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>امضا کارشناس حقوقی / مدیر دفتر:</strong>
                                </td>
                                <td>
                                    <span>
                                        @if($inspectionLog->legal_expert_signature)
											<img style="width:200px;height:200px" src="{{asset("storage/".$inspectionLog->legal_expert_signature)}}" />
										@else
											-
										@endif
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>امضا مدیر دفتر:</strong>
                                </td>
                                <td>
                                    <span>
                                         @if($inspectionLog->office_manager_signature)
											<img style="width:200px;height:200px" src="{{asset("storage/".$inspectionLog->office_manager_signature)}}" />
										@else
											-
										@endif
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>کاربران غیرمجاز:</strong>
                                </td>
                                <td>
                                    <span>
                                        {{$inspectionLog->unauthorized_users ?? "-"}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>صورت جلسه و تعهدات مدیر دفتر / کارشناس حقوقی:</strong>
                                </td>
                                <td>
                                    <span>
                                        {{$inspectionLog->obligations ?? "-"}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>جمع بندی و ارزیابی حاصل از بازرسی مجدد::</strong>
                                </td>
                                <td>
                                    <span>
                                        {{$inspectionLog->second_inspection_summary ?? "-"}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>تاریخ ثبت:</strong>
                                </td>
                                <td>
                                    <span>
                                        {{$inspectionLog->created_at_fa}}
                                    </span>
                                </td>
                            </tr>
                           
                        </tbody>
                    </table>

                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="map-section">
                            <iframe width="100%" height="200" frameborder="0" scrolling="no"
                                marginheight="0" marginwidth="0"
                                src="https://maps.google.com/maps?q=35.756689656604145,51.5674460459345&amp;hl=fa&amp;z=14&amp;output=embed">
                            </iframe>
                            <br>
                            <small>
                                <a
                                    href="https://maps.google.com/maps?q=35.756689656604145,51.5674460459345&amp;hl=fa;z=14&amp;"
                                    style="color:#0000FF;text-align:left" target="_blank" class="d-print-none">
                                    نمایش در صفحه دیگر
                                </a>
                            </small>
                        </div>
					@if ($employees && count($employees))
                        <div class="card" id="employees">
                            <div class="card-header">
                                <h5 class="text-center">اطلاعات منابع انسانی</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered responsive-table">
                                    <thead>
                                        <tr>
                                            
                                            <th scope="col">کد ملی</th>
                                            <th scope="col">نام و نام خانوادگی</th>
                                            <th scope="col">سمت</th>
                                            <th scope="col">وضعیت حضور</th>
                                            <th scope="col">عملکرد و دانش تخصصی</th>
                                            <th scope="col">آراستگی اداری</th>
                                            <th scope="col">همکاری با بازرس</th>
                                            <th scope="col">رضایت ارباب رجوع</th>
                                        </tr>
                                    </thead>
                                    <tbody>

						          @foreach ($employees as $key => $employee)
										
                                        <tr>
                                            
                                            <td  data-label="کد ملی">
                                                {{ isset($employee['national_code']) ? $employee['national_code'] : (isset($officeEmployees[$key]) ? $officeEmployees[$key]['attributes']['national_code']:"") }}</td>
                                            <td  data-label="نام و نام خانوادگی">{{ isset($employee['name']) ? $employee['name']:"" }}</td>
                                            <td  data-label="سمت">{{ isset($employee['job_position']) ? $employee['job_position']:"" }}</td>
                                            {{-- should be checkbox --}}
                                            <td class="question-item"  data-label="وضعیت حضور">
                                            <div>
                                                <div >
                                                    <div >
                                                   {{$employee['presency'] == '1' ? 'بله':'خیر'}}
                                                </div>
                                                <div class="description-wrap mt-2">
                                                    <label for="">توضیحات</label>
                                                    <div>{{$employee['presency_description']}}</div>
                                                </div>
                                                </div>

                                            </div>
                                            </td>
                                            <td class="question-item" data-label="عملکرد و دانش تخصصی">
                                                <div>
                                                    <div >
                                                       {{$employee['knowledge'] == '1' ? 'بله':'خیر'}}
                                                    </div>
                                                    <div class="description-wrap  mt-2">
                                                    <label for="">توضیحات</label>
                                                    <div>{{$employee['knowledge_description']}}</div>
                                                </div>
                                                </div>
                                            </td>
                                            <td class="question-item" data-label="آراستگی اداری">
                                                <div>
                                                    <div >
                                                       {{$employee['office_grooming'] == '1' ? 'بله':'خیر'}}
                                                    </div>
                                                      <div class="description-wrap  mt-2">
                                                    <label for="">توضیحات</label>
                                                    <div>{{$employee['office_grooming_description']}}</div>
                                                </div>
                                                </div>
                                            </td>
                                            <td class="question-item" data-label="همکاری با بازرس">
                                                <div>
                                                    <div >
                                                       {{$employee['cooperation'] == '1' ? 'بله':'خیر'}}
                                                    </div>
                                                     <div class="description-wrap  mt-2">
                                                    <label for="">توضیحات</label>
                                                    <div>{{$employee['cooperation_description']}}</div>
                                                </div>
                                                </div>
                                            </td>
                                            <td class="question-item" data-label="رضایت ارباب رجوع">
                                                <div>
                                                   <div >
                                                       {{$employee['satisfaction'] == '1' ? 'بله':'خیر'}}
                                                    </div>
                                                      <div class="description-wrap  mt-2">
                                                    <label for="">توضیحات</label>
                                                    <div>{{$employee['satisfaction_description']}}</div>
                                                </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
					@endif
                      @foreach ($inspectionLog->checklists()->with("questions","questions.question")->get() as $key=>$checklist)
<div class="checklist-item">
    <h5 class="checklist-item-header text-center mb-4" id="heading{{$key}}">
    <span>
        {{$checklist->category->name}}
    </span>
    </h5>
    <div id="collapse{{$key}}" >
        <div class="checklist-questions">
            @foreach ($checklist->questions as $key=>$question)
                <div>
                    {{ $key + 1 }}. {{ $question->question->question }}
                </div>
                  <div>
                    {{$question->rating == 0 ?'خیر':'بله'}}
                </div>
                <div>
                    <div class='mb-1'>توضیحات:</div>
                    {{$question->description}}
                </div>
                @if($question->general_question_id == 66)
                        <div class="mt-3">
                            <strong class="text-center">مشخصات دوربین</strong>
                            <div class="row">
                                <div class="col-lg-4 my-2">
                                    <strong>آی پی دوربین: </strong>
                                    <span>{{$inspectionLog->cctv_ip}}</span>
                                </div>
                                <div class="col-lg-4 my-2">
                                    <strong>یوزرنیم دوربین: </strong>
                                    <span>{{$inspectionLog->cctv_username}}</span>
                                </div>	
                                <div class="col-lg-4 my-2">
                                    <strong>پسورد دوربین: </strong>
                                    <span>{{$inspectionLog->cctv_password}}</span>
                                </div>	
                                <div class="col-lg-4 my-2">
                                    <strong>پورت دوربین: </strong>
                                    <span>{{$inspectionLog->cctv_port}}</span>
                                </div>	
                            </div>
                        </div>
                @endif
                <hr>
            @endforeach

        </div>
    </div>
</div>
@endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
        </div>
    </section>
@endsection
@push('after_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	
	<script>
		$(document).ready(function(){
			window.print()
		})
	</script>
</script>
@endpush