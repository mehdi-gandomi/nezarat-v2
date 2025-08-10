@php
    $employees = json_decode($crud->entry->employees, true);
    $categories = \App\Models\GeneralQuestionCategory::with('questions')->get();
@endphp
@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('backpack::crud.preview') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp
@push('after_styles')
	<meta http-equiv="ScreenOrientation" content="autoRotate:disabled">
    <link rel="stylesheet" href="{{ asset('assets/css/persian-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive-table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.steps.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.fileuploader.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/css/toastr.min.css')}}">
    <style>
        .text-right{
            text-align: right !important;
        }
        .signature-pad {
            border: 1px solid #4c428b;
            border-radius: 10px;
        }

        @media(max-width:768px) {
            .signature-pad {
                width: 100% !important;
                height: 200px !important;
            }
        }
        /**
 * FilePond Custom Styles
 */
.filepond--drop-label {
	color: #4c4e53;
}

.filepond--label-action {
	text-decoration-color: #babdc0;
}

.filepond--panel-root {
	border-radius: 2em;
	background-color: #edf0f4;
	height: 1em;
}

.filepond--item-panel {
	background-color: #595e68;
}

.filepond--drip-blob {
	background-color: #7f8a9a;
}

    </style>
@endpush
@section('header')
    <div class="container-fluid d-flex justify-content-between my-3">
        <section class="header-operation animated fadeIn d-flex mb-2 align-items-baseline d-print-none"
            bp-section="page-header">
            <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
            <p class="ms-2 ml-2 mb-0" bp-section="page-subheading">{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')) . ' ' . $crud->entity_name !!}</p>
            @if ($crud->hasAccess('list'))
                <p class="ms-2 ml-2 mb-0" bp-section="page-subheading-back-button">
                    <small><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i>
                            {{ trans('backpack::crud.back_to_all') }}
                            <span>{{ $crud->entity_name_plural }}</span></a></small>
                </p>
            @endif
        </section>
        <a href="javascript: window.print();" class="btn float-end float-right"><i class="la la-print"></i></a>
    </div>
@endsection

@section('content')
    <div class="row" bp-section="crud-operation-show">
        <div class="{{ $crud->getShowContentClass() }}">
            <form method="POST" enctype="multipart/form-data" id="inspectionForm" action="{{route('office-file.submit-inspection',['id'=>$crud->entry->id])}}">
                @csrf
                <input type="hidden" name="lat" id="lat" />
                <input type="hidden" name="lng" id="lng" />
				<input type="hidden" name="user_id" value="{{auth('backpack')->user()->id}}" />
                <input type="hidden" name="inspector_signature" id="inspector_signature" />
                <input type="hidden" name="office_manager_signature" id="office_manager_signature" />
                <input type="hidden" name="legal_expert_signature" id="legal_expert_signature" />
				<h5 class="text-center my-3">ثبت صورتجلسه</h5>
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-center">مشخصات عمومی</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered responsive-table">
                            <tbody>
                                <tr>
                                    <td class="text-right">
                                        <div>
                                            <strong>کد دفتر:</strong>
                                            <span>{{ $crud->entry->office_code }}</span>
                                        </div>
                                        <div class="mt-1">
                                            مختصات بازرس: <span id="coordinates"></span>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <strong>نام مدیر دفتر:</strong>
                                        <span>{{ $crud->entry->first_name }} {{ $crud->entry->last_name }}</span>
                                    </td>
                                </tr>
                                <tr>
								<td class="text-right">
                                        <strong>تصویر مدیر دفتر:</strong>
                                        <span>
											<img style="width:100px" src="{{db_asset($crud->entry->personel_image)}}"/>
										</span>
                                    </td>
                                    <td class="text-right">
                                        <strong>آدرس دفتر:</strong>
                                        <span>{{ $crud->entry->address }}</span>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>دوره بازرسی:</strong>
                                        <!--<span>
                                            <input type="text" class="form-control" name="inspection_period">
                                        </span>-->
										<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="inspection_type" checked id="inlineRadio1" value="1">
									<label class="form-check-label" for="inlineRadio1">موردی</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="inspection_type" id="inlineRadio2" value="2">
									<label class="form-check-label" for="inlineRadio2">دوره ای</label>
								</div>
								<div id="periodic-inspection-type" class="mt-3 d-none">
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="inspection_season" checked id="inlineRadio11" value="1">
											<label class="form-check-label" for="inlineRadio11">بهار</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="inspection_season" id="inlineRadio12" value="2">
											<label class="form-check-label" for="inlineRadio12">تابستان</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="inspection_season" id="inlineRadio13" value="3">
											<label class="form-check-label" for="inlineRadio13">پاییز</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="inspection_season" id="inlineRadio14" value="4">
											<label class="form-check-label" for="inlineRadio14">زمستان</label>
										</div>
								</div>
                                    </td>
                                    <td class="text-right">
                                        <strong>تاریخ بازرسی:</strong>
                                        <span>
                                            <input type="text" class="form-control" id="inspection_date">
                                            <input type="hidden" class="form-control" name="inspection_date"
                                                id="inspection_date_alt">
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                @if ($employees && count($employees))
                    <div class="card" id="employees">
                        <div class="card-header">
                            <h6 class="text-center">اطلاعات منابع انسانی</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered responsive-table">
                                <thead>
                                    <th scope="col">ردیف</th>
                                    <th scope="col">کد ملی</th>
                                    <th scope="col">نام و نام خانوادگی</th>
                                    <th scope="col">سمت</th>
                                    <th scope="col">وضعیت حضور</th>
                                    <th scope="col">عملکرد و دانش تخصصی</th>
                                    <th scope="col">آراستگی اداری</th>
                                    <th scope="col">همکاری با بازرس</th>
                                    <th scope="col">رضایت ارباب رجوع</th>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $key => $employee)
                                        <tr>
                                            <td  data-label="ردیف">{{ $key + 1 }}</td>
                                            <td  data-label="کد ملی">
                                                {{ isset($employee['national_code']) ? $employee['national_code'] : '' }}<input type="hidden" name="employees[{{ $key }}][national_code]" value="{{ isset($employee['national_code']) ? $employee['national_code']:""  }}" /></td>
                                            <td  data-label="نام و نام خانوادگی">{{ $employee['attributes']['name'] }} <input type="hidden" name="employees[{{ $key }}][name]" value="{{ $employee['attributes']['name'] }}" /> </td>
                                            <td  data-label="سمت">{{ $employee['attributes']['job_position'] }} <input type="hidden" name="employees[{{ $key }}][job_position]" value="{{ $employee['attributes']['job_position'] }}" /></td>
                                            {{-- should be checkbox --}}
                                            <td class="question-item"  data-label="وضعیت حضور">
                                            <div>
                                                <div class="radio-wrap">
                                                    <div class="form-check form-check-inline">
                                                        <input checked class="form-check-input" type="radio"
                                                            name="employees[{{ $key }}][presency]" value="1">
                                                        <label class="form-check-label" for="inlineRadio1">بله</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="employees[{{ $key }}][presency]" value="0">
                                                        <label class="form-check-label" for="inlineRadio2">خیر</label>
                                                    </div>
                                                </div>
                                                <div class="description-wrap">
                                                    <label for="">توضیحات</label>
                                                    <textarea name="employees[{{ $key }}][presency_description]" class="form-control" cols="5"
                                                        rows="1"></textarea>
                                                </div>
                                            </div>
                                            </td>
                                            <td class="question-item" data-label="عملکرد و دانش تخصصی">
                                                <div>
                                                    <div class="radio-wrap">
                                                        <div class="form-check form-check-inline">
                                                            <input checked class="form-check-input" type="radio"
                                                                name="employees[{{ $key }}][knowledge]" value="1">
                                                            <label class="form-check-label" for="inlineRadio1">بله</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="employees[{{ $key }}][knowledge]" value="0">
                                                            <label class="form-check-label" for="inlineRadio2">خیر</label>
                                                        </div>
                                                    </div>
                                                    <div class="description-wrap">
                                                        <label for="">توضیحات</label>
                                                        <textarea name="employees[{{ $key }}][knowledge_description]" class="form-control" cols="5"
                                                            rows="1"></textarea>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="question-item" data-label="آراستگی اداری">
                                                <div>
                                                    <div class="radio-wrap">
                                                        <div class="form-check form-check-inline">
                                                            <input checked class="form-check-input" type="radio"
                                                                name="employees[{{ $key }}][office_grooming]"
                                                                value="1">
                                                            <label class="form-check-label" for="inlineRadio1">بله</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="employees[{{ $key }}][office_grooming]"
                                                                value="0">
                                                            <label class="form-check-label" for="inlineRadio2">خیر</label>
                                                        </div>
                                                    </div>
                                                    <div class="description-wrap">
                                                        <label for="">توضیحات</label>
                                                        <textarea name="employees[{{ $key }}][office_grooming_description]" class="form-control" cols="5"
                                                            rows="1"></textarea>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="question-item" data-label="همکاری با بازرس">
                                                <div>
                                                    <div class="radio-wrap">
                                                        <div class="form-check form-check-inline">
                                                            <input checked class="form-check-input" type="radio"
                                                                name="employees[{{ $key }}][cooperation]"
                                                                value="1">
                                                            <label class="form-check-label" for="inlineRadio1">بله</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="employees[{{ $key }}][cooperation]"
                                                                value="0">
                                                            <label class="form-check-label" for="inlineRadio2">خیر</label>
                                                        </div>
                                                    </div>
                                                    <div class="description-wrap">
                                                        <label for="">توضیحات</label>
                                                        <textarea name="employees[{{ $key }}][cooperation_description]" class="form-control" cols="5"
                                                            rows="1"></textarea>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="question-item" data-label="رضایت ارباب رجوع">
                                                <div>
                                                    <div class="radio-wrap">
                                                        <div class="form-check form-check-inline">
                                                            <input checked class="form-check-input" type="radio"
                                                                name="employees[{{ $key }}][satisfaction]"
                                                                value="1">
                                                            <label class="form-check-label" for="inlineRadio1">بله</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="employees[{{ $key }}][satisfaction]"
                                                                value="0">
                                                            <label class="form-check-label" for="inlineRadio2">خیر</label>
                                                        </div>
                                                    </div>
                                                    <div class="description-wrap">
                                                        <label for="">توضیحات</label>
                                                        <textarea name="employees[{{ $key }}][satisfaction_description]" class="form-control" cols="5"
                                                            rows="1"></textarea>
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
                <div>
                    <label>کاربران غیرمجاز</label>
                    <textarea class="form-control mt-2 mb-3" rowx="5" name="unauthorized_users" ></textarea>
                </div>
                <div class="question-wrap">
                    <h6 class="text-center">اطلاعات وارد شده در سامانه مطابقت دارد</h6>
                    <div class="d-flex justify-content-center gap-2">
                        <button onclick="showWizard()" class="btn text-white btn-success btn-sm">
                            بله
                        </button>
                        <button onclick="submitNoAdapt()" class="btn btn-danger btn-sm text-white">
                            خیر
                        </button>
                    </div>
                </div>
                <div id="wizard" class="d-none mt-4 ">
                    @foreach ($categories as $category)
                        <h4 class="wizard-header">{{ $category->name }}</h4>
                        <section class="wizard-body">
                            @foreach ($category->questions as $key => $question)
                                <div>
                                    <div>
                                        <div>
                                            {{ $key + 1 }}. {{ $question->question }}
                                        </div>
                                        @if($category->id == 6)
											<div class="mt-2">
												<div class="radio-wrap">
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio"
															name="category[{{$category->id}}][questions][{{ $question->id }}][rating]" value="10">
														<label class="form-check-label" for="inlineRadio1">ضعیف</label>
													</div>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio"
															name="category[{{$category->id}}][questions][{{ $question->id }}][rating]" value="11" checked >
														<label class="form-check-label" for="inlineRadio2">خوب</label>
													</div>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio"
															name="category[{{$category->id}}][questions][{{ $question->id }}][rating]" value="12" >
														<label class="form-check-label" for="inlineRadio2">متوسط</label>
													</div>
												</div>
											</div>
										@else
											<div class="mt-2">
												<div class="radio-wrap">
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio"
															name="category[{{$category->id}}][questions][{{ $question->id }}][rating]" checked value="1">
														<label class="form-check-label" for="inlineRadio1">بله</label>
													</div>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio"
															name="category[{{$category->id}}][questions][{{ $question->id }}][rating]" value="0"  >
														<label class="form-check-label" for="inlineRadio2">خیر</label>
													</div>
												</div>
											</div>
										@endif
                                        <div class="description-wrap mt-1">
                                            <label for="">توضیحات</label>
                                            <textarea name="category[{{$category->id}}][questions][{{ $question->id }}][description]" class="form-control" cols="8" rows="2"></textarea>
                                        </div>
										@if($category->id == 4 && $question->id == 66)
											<div class="mt-3">
												<p class="text-center">مشخصات دوربین</p>
												<div class="row">
													<div class="col-lg-4">
														<label for="cctv_ip">آی پی دوربین</label>
														<input type="text" name="cctv_ip" id="cctv_ip" class="form-control" />
													</div>
													<div class="col-lg-4">
														<label for="cctv_username">یوزرنیم دوربین</label>
														<input type="text" name="cctv_username" id="cctv_username" class="form-control" />
													</div>	
													<div class="col-lg-4">
														<label for="cctv_password">پسورد دوربین</label>
														<input type="text" name="cctv_password" id="cctv_password" class="form-control" />
													</div>	
													<div class="col-lg-4">
														<label for="cctv_port">پورت دوربین</label>
														<input type="text" name="cctv_port" id="cctv_port" class="form-control" />
													</div>	
												</div>
											</div>
										@endif

                                    </div>
                                    <hr class="my-4">
                                </div>
                            @endforeach
                            <!--<div class="description-wrap mt-1">
                                <label for="">درصد انطباق</label>
                                <input type="number" class="form-control" name="category[{{$category->id}}][adapt_percent]">
                            </div>-->
							
                        </section>
                    @endforeach
                    <h4 class="wizard-header">صورت جلسه و تعهدات</h4>
                    <section class="wizard-body">
                        <div>
                            <div class="form-group">
                                <label for="">صورت جلسه و تعهدات مدیر دفتر / کارشناس حقوقی:</label>
                                <textarea class="form-control" name="obligations" id="" cols="30" rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">جمع بندی و ارزیابی حاصل از بازرسی مجدد:</label>
                                <textarea class="form-control" name="second_inspection_summary" id="" cols="30" rows="10"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <span>آیا بازرسی مجدد نیاز است؟</span>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="requires_second_inspection"
                                            id="inlineRadio1" value="1">
                                        <label class="form-check-label" for="inlineRadio1">بله</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input checked class="form-check-input" type="radio" name="requires_second_inspection"
                                            id="inlineRadio2" value="0">
                                        <label class="form-check-label" for="inlineRadio2">خیر</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <span>تاریخ بازرسی جدید</span>
                                    <span>
                                        <input type="text" class="form-control" id="second_inspection_date">
                                        <input type="hidden" class="form-control" name="second_inspection_date"
                                            id="second_inspection_date_alt">
                                    </span>
                                </div>
                            </div>
							   <div class="row my-4">
                                <div class="col-lg-12">
                                    <label for="">فایل (ها) ضمیمه</label>
                                    <input type="file"
                                    id="attachments"
                                    name="attachments[]"
                                    multiple
                                     />
                                    {{-- <input id="attachments" name="attachments[]" multiple type="file" class="filepond"> --}}
                                    {{-- @include('vendor.backpack.crud.fields.browse_multiple',['field'=>[]]) --}}

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div>
                                        <label for="">امضا بازرس</label>
                                    </div>
                                    <div class="signature-wrap">
                                        <canvas id="signature_pad_inspector" class="signature-pad mt-3" width=400
                                        height=200></canvas>
                                        <button onclick="clearSignature('inspector')" class="btn btn-secondary btn-sm" type="button">پاک کردن</button>
										<button onclick="saveSignature('inspector')" class="btn btn-success btn-sm" type="button">تایید</button>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div>
                                        <label for="">امضا مدیر دفتر</label>
                                    </div>
                                    <div class="signature-wrap">
                                        <canvas id="signature_pad_manager" class="signature-pad mt-3" width=400
                                        height=200></canvas>
                                        <button onclick="clearSignature('manager')" class="btn btn-secondary btn-sm" type="button">پاک کردن</button>
										<button onclick="saveSignature('manager')" class="btn btn-success btn-sm" type="button">تایید</button>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div>
                                        <label for="">امضا کارشناس حقوقی / مدیر دفتر</label>
                                    </div>
                                    <div class="signature-wrap">
                                        <canvas id="signature_pad_expert" class="signature-pad mt-3" width=400
                                        height=200></canvas>
                                        <button onclick="clearSignature('expert')" class="btn btn-secondary btn-sm" type="button">پاک کردن</button>
										<button onclick="saveSignature('expert')" class="btn btn-success btn-sm" type="button">تایید</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>

                </div>
            </form>

        </div>
    </div>
@endsection
@push('after_scripts')
    <script src="{{ asset('assets/js/persian-date.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/js/persian-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/signature_pad.umd.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.fileuploader.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>

    <script>
	$(document).ready(function(e){
			$('input[type=radio][name=inspection_type]').change(function() {
		$("#periodic-inspection-type").addClass('d-none')
		if (this.value == '2') {
			$("#periodic-inspection-type").removeClass('d-none')
		}
		
	});
	});
// 	let portrait = window.matchMedia("(orientation: portrait)");
// 	if(!portrait.matches) {
// 		alert("لطفا موبایل را در حالت عمودی قرار دهید")
// 	}
// 	portrait.addEventListener("change", function(e) {
// 		if(!e.matches) {
// 			alert("لطفا موبایل را در حالت عمودی قرار دهید")
// 			e.preventDefault()
// 			screen.orientation.lock('portrait-primary');
// 			return false;
// 		} 
// 		return true;
// 	})
// })
        var signaturePadInspector;
        var signaturePadManager;
        var signaturePadExpert;

        window.shouldSubmit=false
        function clearSignature(type){
            if(type == 'expert') {
                signaturePadExpert.clear()
				signaturePadExpert.on()
            }else if(type == 'manager') {
                signaturePadManager.clear()
				signaturePadManager.on()
            }else if(type == 'inspector') {
                signaturePadInspector.clear()
				signaturePadInspector.on()
            }
        }
		 function saveSignature(type){
            if(type == 'expert') {
                signaturePadExpert.off()
            }else if(type == 'manager') {
                signaturePadManager.off()
            }else if(type == 'inspector') {
                signaturePadInspector.off()
            }
        }
        document.addEventListener("DOMContentLoaded",e=>{
            $("#coordinates").html('درحال دریافت اطلاعات')
            navigator.geolocation.getCurrentPosition(
                // Success callback function
                (position) => {
                // Get the user's latitude and longitude coordinates
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                $("#lat").val(lat)
                $("#lng").val(lng)
                // Do something with the location data, e.g. display on a map
                $("#coordinates").html(`${lat},${lng}`)
                },
                // Error callback function
                (error) => {
                // Handle errors, e.g. user denied location sharing permissions
                // console.error("Error getting user location:", error);
                $("#coordinates").html('خطا در دریافت اطلاعات')
                }
            );
         })
         // Adjust canvas coordinate space taking into account pixel ratio,
        // to make it look crisp on mobile devices.
        // This also causes canvas to be cleared.
        function resizeCanvas(canvas_inspector,canvas_manager,canvas_expert) {
            // When zoomed out to less than 100%, for some very strange reason,
            // some browsers report devicePixelRatio as less than 1
            // and only part of the canvas is cleared then.
            var ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas_inspector.width = canvas_inspector.offsetWidth * ratio;
            canvas_inspector.height = canvas_inspector.offsetHeight * ratio;
            canvas_inspector.getContext("2d").scale(ratio, ratio);

            canvas_manager.width = canvas_manager.offsetWidth * ratio;
            canvas_manager.height = canvas_manager.offsetHeight * ratio;
            canvas_manager.getContext("2d").scale(ratio, ratio);

            canvas_expert.width = canvas_expert.offsetWidth * ratio;
            canvas_expert.height = canvas_expert.offsetHeight * ratio;
            canvas_expert.getContext("2d").scale(ratio, ratio);


        }
        function submitNoAdapt(){
            swal("علت عدم انطباق اطلاعات اولیه و کارکنان در سامانه رد میشود. آیا مطمئنید؟", {
                buttons: {
                    cancel: "انصراف",
                    confirm: {
                    text: "بله",
                    value: 1,
                    },
                },
                })
                .then((value) => {
                    if(value){
                        $.post("{{route('office-file.submit-no-adapt',['id'=>$crud->entry->id])}}",$("#inspectionForm").serialize(),(response)=>{
                            if(response.ok){
                                swal("موفق!", "با موفقیت ثبت شد", "success").then(value=>{
									location.href="{{route('inspection-log.index',['office_code'=>$crud->entry->office_code])}}";
								});
                            }
                        })
                    }
                });
        }
        function showWizard(){
            $("#wizard").removeClass('d-none')
            $("#employees").addClass('d-none')
            $(".question-wrap").addClass('d-none')
            $("#wizard").steps({
                headerTag: "h4",
                bodyTag: "section",
                //transitionEffect: "slideRight",
                autoFocus: true,
                onFinishing:function (event, currentIndex){
                    console.log("finished")

                    if($("#lat").val() && $("#lng").val()){
						window.shouldSubmit=true;
                        $("#inspectionForm").submit()
                    }else{
                        console.log("getting location")
                        toastr.warning('لطفا دسترسی لوکیشن را فعال نمایید')
                        navigator.geolocation.getCurrentPosition(
                            // Success callback function
                            (position) => {
                                window.shouldSubmit=true
                            // Get the user's latitude and longitude coordinates
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            $("#lat").val(lat)
                            $("#lng").val(lng)
                            // Do something with the location data, e.g. display on a map
                            $("#coordinates").html(`${lat},${lng}`)
                            $("#inspectionForm").submit()
                            },
                            // Error callback function
                            (error) => {
                            // Handle errors, e.g. user denied location sharing permissions
                            // console.error("Error getting user location:", error);
                            $("#coordinates").html('خطا در دریافت اطلاعات')
                            }
                        );
                    }

                },
                onStepChanging: function (event, currentIndex, newIndex) {
       if(newIndex == 6){

        setTimeout(() => {
			  $("#second_inspection_date").pDatepicker({
            autoClose: true,
            format: "YYYY/MM/DD",
            altField: "#second_inspection_date_alt",
            altFieldFormatter: (unixDate) => {
                const d = new Date(unixDate)
                return `${d.getFullYear()}-${("0" + (d.getMonth()+1)).slice(-2) }-${d.getDate()}`
            }
        });
            $('#attachments').fileuploader({
        addMore: true
    });
            var canvas_inspector = document.getElementById('signature_pad_inspector');
        var canvas_manager = document.getElementById('signature_pad_manager');
        var canvas_expert = document.getElementById('signature_pad_expert');

        window.onresize = resizeCanvas;
        resizeCanvas(canvas_inspector,canvas_manager,canvas_expert);

        signaturePadInspector = new SignaturePad(canvas_inspector, {
        backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
        });

        signaturePadManager = new SignaturePad(canvas_manager, {
        backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
        });

        signaturePadExpert = new SignaturePad(canvas_expert, {
        backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
        });
        }, 500);

       }
       return true;
    },
                labels: {
                    cancel: "انصراف",
                    current: "فعلی",
                    pagination: "صفحه بندی",
                    finish: "پایان",
                    next: "مرحله بعدی",
                    previous: "مرحله قبلی",
                    loading: "در حال بارگزاری"
                }

            });

        }


        $("#inspectionForm").on('submit',e=>{
            if(!shouldSubmit) return e.preventDefault()
            $("#inspector_signature").val(signaturePadInspector.toDataURL())
            $("#office_manager_signature").val(signaturePadManager.toDataURL())
            $("#legal_expert_signature").val(signaturePadExpert.toDataURL())
            return e.target.submit();
        })

        $("#inspection_date").pDatepicker({
            autoClose: true,
            format: "YYYY/MM/DD",
            altField: "#inspection_date_alt",
            altFieldFormatter: (unixDate) => {
                const d = new Date(unixDate)
                return `${d.getFullYear()}-${("0" + (d.getMonth()+1)).slice(-2) }-${d.getDate()}`
            }
        });

    </script>
@endpush
