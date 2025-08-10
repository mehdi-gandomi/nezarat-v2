@php
    $employees=json_decode($crud->entry->employees,true);
    $categories=\App\Models\GeneralQuestionCategory::with("questions")->get();
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
    <link rel="stylesheet" href="{{asset('assets/css/persian-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/responsive-table.css')}}">
    <style>
        .signature-pad{
            border: 1px solid #4c428b;
            border-radius: 10px;
        }
        @media(max-width:768px){
            .signature-pad{
                width: 100% !important;
                height: 200px !important;
            }
        }
    </style>
@endpush
@section('header')
    <div class="container-fluid d-flex justify-content-between my-3">
        <section class="header-operation animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
            <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
            <p class="ms-2 ml-2 mb-0" bp-section="page-subheading">{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}</p>
            @if ($crud->hasAccess('list'))
                <p class="ms-2 ml-2 mb-0" bp-section="page-subheading-back-button">
                    <small><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
                </p>
            @endif
        </section>
        <a href="javascript: window.print();" class="btn float-end float-right"><i class="la la-print"></i></a>
    </div>
@endsection

@section('content')
<div class="row" bp-section="crud-operation-show">
    <div class="{{ $crud->getShowContentClass() }}">

        <div class="card">
            <div class="card-header">
                <h6 class="text-center">مشخصات عمومی</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <strong>کد دفتر:</strong>
                                <span>{{$crud->entry->office_code}}</span>
                            </td>
                            <td>
                                <strong>نام مدیر دفتر:</strong>
                                <span>{{$crud->entry->first_name}} {{$crud->entry->last_name}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>آدرس دفتر:</strong>
                                <span>{{$crud->entry->address}}</span>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <strong>دوره بازرسی:</strong>
                                <span>
                                    <input type="text" class="form-control" name="inspection_period">
                                </span>
                            </td>
                            <td>
                                <strong>تاریخ بازرسی:</strong>
                                <span>
                                    <input type="text" class="form-control" id="inspection_date" >
                                    <input type="hidden" class="form-control" name="inspection_date" id="inspection_date_alt">
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h6 class="text-center">اطلاعات منابع انسانی</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
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
                        @foreach ($employees as $key=>$employee)
                            <tr>
                                <td data-label="ردیف">{{($key+1)}}</td>
                                <td data-label="کد ملی">{{isset($employee['national_code']) ? $employee['national_code']:""}}</td>
                                <td data-label="نام و نام خانوادگی">{{$employee['attributes']['name']}}</td>
                                <td data-label="سمت">{{$employee['attributes']['job_position']}}</td>
                                {{-- should be checkbox --}}
                                <td data-label="وضعیت حضور">
                                    <div class="radio-wrap">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employees[{{$key}}]['presency']"  value="1">
                                            <label class="form-check-label" for="inlineRadio1">بله</label>
                                          </div>
                                          <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employees[{{$key}}]['presency']"  value="0">
                                            <label class="form-check-label" for="inlineRadio2">خیر</label>
                                          </div>
                                    </div>
                                    <div class="description-wrap">
                                        <label for="">توضیحات</label>
                                        <textarea name="employees[{{$key}}]['presency_description']" class="form-control"  cols="5" rows="1"></textarea>
                                    </div>
                                </td>
                                <td data-label="عملکرد و دانش تخصصی">
                                    <div class="radio-wrap">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employees[{{$key}}]['knowledge']"  value="1">
                                            <label class="form-check-label" for="inlineRadio1">بله</label>
                                          </div>
                                          <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employees[{{$key}}]['knowledge']"  value="0">
                                            <label class="form-check-label" for="inlineRadio2">خیر</label>
                                          </div>
                                    </div>
                                    <div class="description-wrap">
                                        <label for="">توضیحات</label>
                                        <textarea name="employees[{{$key}}]['knowledge_description']" class="form-control"  cols="5" rows="1"></textarea>
                                    </div>
                                </td>
                                <td data-label="آراستگی اداری">
                                    <div class="radio-wrap">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employees[{{$key}}]['office_grooming']"  value="1">
                                            <label class="form-check-label" for="inlineRadio1">بله</label>
                                          </div>
                                          <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employees[{{$key}}]['office_grooming']"  value="0">
                                            <label class="form-check-label" for="inlineRadio2">خیر</label>
                                          </div>
                                    </div>
                                    <div class="description-wrap">
                                        <label for="">توضیحات</label>
                                        <textarea name="employees[{{$key}}]['office_grooming_description']" class="form-control"  cols="5" rows="1"></textarea>
                                    </div>
                                </td>
                                <td data-label="همکاری با بازرس">
                                    <div class="radio-wrap">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employees[{{$key}}]['cooperation']"  value="1">
                                            <label class="form-check-label" for="inlineRadio1">بله</label>
                                          </div>
                                          <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employees[{{$key}}]['cooperation']"  value="0">
                                            <label class="form-check-label" for="inlineRadio2">خیر</label>
                                          </div>
                                    </div>
                                    <div class="description-wrap">
                                        <label for="">توضیحات</label>
                                        <textarea name="employees[{{$key}}]['cooperation_description']" class="form-control"  cols="5" rows="1"></textarea>
                                    </div>
                                </td>
                                <td data-label="رضایت ارباب رجوع">
                                    <div class="radio-wrap">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employees[{{$key}}]['satisfaction']"  value="1">
                                            <label class="form-check-label" for="inlineRadio1">بله</label>
                                          </div>
                                          <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employees[{{$key}}]['satisfaction']"  value="0">
                                            <label class="form-check-label" for="inlineRadio2">خیر</label>
                                          </div>
                                    </div>
                                    <div class="description-wrap">
                                        <label for="">توضیحات</label>
                                        <textarea name="employees[{{$key}}]['satisfaction_description']" class="form-control"  cols="5" rows="1"></textarea>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        @foreach ($categories as $category)
        <div class="card">
            <div class="card-header">
                <h6 class="text-center">{{$category->name}}</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <th scope="col">معیارهای مورد ارزیابی</th>
                        <th scope="col">امتیاز ارزیابی</th>
                        <th scope="col">توضیحات</th>
                    </thead>
                    <tbody>

                        @foreach ($category->questions as $key=>$question)
                            <tr>
                                <td data-label="معیارهای مورد ارزیابی">{{($question->question)}}</td>
                                <td data-label="امتیاز ارزیابی">
                                    <div class="radio-wrap">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="questions[{{$question->id}}]['rating']"  value="1">
                                            <label class="form-check-label" for="inlineRadio1">ضعیف</label>
                                          </div>
                                          <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="questions[{{$question->id}}]['rating']"  value="2">
                                            <label class="form-check-label" for="inlineRadio2">متوسط</label>
                                          </div>
                                          <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="questions[{{$question->id}}]['rating']"  value="3">
                                            <label class="form-check-label" for="inlineRadio2">خوب</label>
                                          </div>
                                    </div>
                                </td>

                                <td data-label="توضیحات">
                                    <div class="description-wrap">
                                        <label for="">توضیحات</label>
                                        <textarea name="questions[{{$question->id}}]['description']" class="form-control"  cols="8" rows="1"></textarea>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
        <div class="card">
            <div class="card-header">
                صورت جلسه و تعهدات
            </div>
            <div class="card-body">
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
                            <input class="form-check-input" type="radio" name="requires_second_inspection" id="inlineRadio1" value="1">
                            <label class="form-check-label" for="inlineRadio1">بله</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="requires_second_inspection" id="inlineRadio2" value="0">
                            <label class="form-check-label" for="inlineRadio2">خیر</label>
                          </div>
                    </div>
                    <div class="col-lg-6">
                        <span>تاریخ بازرسی جدید</span>
                        <span>
                            <input type="text" class="form-control" id="second_inspection_date" >
                            <input type="hidden" class="form-control" name="second_inspection_date" id="second_inspection_date_alt">
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div>
                            <label for="">امضا بازرس</label>
                        </div>
                        <canvas id="signature_pad_inspector" class="signature-pad mt-3" width=400 height=200></canvas>
                    </div>
                    <div class="col-lg-4">
                        <div>
                            <label for="">امضا مدیر دفتر</label>
                        </div>
                        <canvas id="signature_pad_manager" class="signature-pad mt-3" width=400 height=200></canvas>
                    </div>
                    <div class="col-lg-4">
                        <div>
                            <label for="">امضا کارشناس حقوقی / مدیر دفتر</label>
                        </div>
                        <canvas id="signature_pad_expert" class="signature-pad mt-3" width=400 height=200></canvas>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
@push('after_scripts')
    <script src="{{asset('assets/js/persian-date.min.js')}}"></script>
    <script src="{{asset('assets/js/persian-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/signature_pad.umd.min.js')}}"></script>

    <script>

        var canvas_inspector = document.getElementById('signature_pad_inspector');
        var canvas_manager = document.getElementById('signature_pad_manager');
        var canvas_expert = document.getElementById('signature_pad_expert');
        // Adjust canvas coordinate space taking into account pixel ratio,
        // to make it look crisp on mobile devices.
        // This also causes canvas to be cleared.
        function resizeCanvas() {
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

        window.onresize = resizeCanvas;
        resizeCanvas();

        var signaturePadInspector = new SignaturePad(canvas_inspector, {
        backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
        });

        var signaturePadManager = new SignaturePad(canvas_manager, {
        backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
        });

        var signaturePadExpert = new SignaturePad(canvas_expert, {
        backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
        });

        // document.getElementById('save-png').addEventListener('click', function () {
        // if (signaturePadInspector.isEmpty()) {
        //     return alert("Please provide a signature first.");
        // }

        // var data = signaturePadInspector.toDataURL('image/png');
        // console.log(data);
        // window.open(data);
        // });

        // document.getElementById('save-jpeg').addEventListener('click', function () {
        // if (signaturePadInspector.isEmpty()) {
        //     return alert("Please provide a signature first.");
        // }

        // var data = signaturePadInspector.toDataURL('image/jpeg');
        // console.log(data);
        // window.open(data);
        // });

        // document.getElementById('save-svg').addEventListener('click', function () {
        // if (signaturePadInspector.isEmpty()) {
        //     return alert("Please provide a signature first.");
        // }

        // var data = signaturePadInspector.toDataURL('image/svg+xml');
        // console.log(data);
        // console.log(atob(data.split(',')[1]));
        // window.open(data);
        // });

        // document.getElementById('clear').addEventListener('click', function () {
        //     signaturePadInspector.clear();
        // });

        // document.getElementById('undo').addEventListener('click', function () {
        //     var data = signaturePadInspector.toData();
        // if (data) {
        //     data.pop(); // remove the last dot or line
        //     signaturePadInspector.fromData(data);
        // }
        // });


        $("#inspection_date").pDatepicker({
            autoClose: true,
            format:"YYYY/MM/DD",
            altField:"#inspection_date_alt",
            altFieldFormatter:(unixDate)=>{
                const d=new Date(unixDate)
                return `${d.getFullYear()}-${("0" + (d.getMonth()+1)).slice(-2) }-${d.getDate()}`
            }
        });
        $("#second_inspection_date").pDatepicker({
            autoClose: true,
            format:"YYYY/MM/DD",
            altField:"#second_inspection_date_alt",
            altFieldFormatter:(unixDate)=>{
                const d=new Date(unixDate)
                return `${d.getFullYear()}-${("0" + (d.getMonth()+1)).slice(-2) }-${d.getDate()}`
            }
        });

    </script>
@endpush
