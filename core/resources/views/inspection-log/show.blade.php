@php

    $employees = $crud->entry->employees;
	$officeEmployees = json_decode($crud->entry->office->employees, true);
	
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
<link rel="stylesheet" href="{{ asset('assets/css/responsive-table.css') }}">
    <link rel="stylesheet" href="{{asset('assets/css/lightbox.min.css')}}">
    <script src="{{asset('assets/js/lightbox.min.js')}}"></script>
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
        <a href="/admin/inspection-log/{{$crud->entry->id}}/print" class="btn float-end float-right"><i class="la la-print"></i></a>
    </div>
@endsection

@section('content')
<div class="row" bp-section="crud-operation-show">
    <div class="{{ $crud->getShowContentClass() }}">

	{{-- Default box --}}
	<div class="">
	@if ($crud->model->translationEnabled())
		<div class="row">
			<div class="col-md-12 mb-2">
				{{-- Change translation button group --}}
				<div class="btn-group float-right">
				<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					{{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[request()->input('_locale')?request()->input('_locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					@foreach ($crud->model->getAvailableLocales() as $key => $locale)
						<a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}?_locale={{ $key }}">{{ $locale }}</a>
					@endforeach
				</ul>
				</div>
			</div>
		</div>
	@endif
		@if($crud->tabsEnabled() && count($crud->getUniqueTabNames('columns')))
			@include('crud::inc.show_tabbed_table')
		@else
			<div class="card no-padding no-border mb-0">
				<table class="table table-striped m-0 p-0">
					<tbody>
						<tr>
							<td class="border-top-0">
								<strong>نام مدیر دفتر:</strong>
							</td>
							<td class="border-top-0">
													<span>
									{{ $crud->entry->office->first_name }} {{ $crud->entry->office->last_name }}
						</span>
							</td>
						</tr>
							<tr>
							<td class="border-top-0">
								<strong>تصویر مدیر دفتر:</strong>
							</td>
							<td class="border-top-0">
													<span>
									<img style="width:100px" src="{{db_asset($crud->entry->office->personel_image)}}"/>
						</span>
							</td>
						</tr>
					</tbody>
				</table>
				@include('crud::inc.show_table', ['columns' => $crud->columns()])
			
			</div>
		@endif
        <div class="card">
            <div class="card-body">
				<div class="map-section">
					<iframe
						width="100%"
						height="200"
						frameborder="0"
						scrolling="no"
						marginheight="0"
						marginwidth="0"
						src="https://maps.google.com/maps?q={{$crud->entry->lat}},{{$crud->entry->lng}}&hl=fa&z=14&amp;output=embed"
						>
						</iframe>
						<br />
						<small>
						<a
							href="https://maps.google.com/maps?q={{$crud->entry->lat}},{{$crud->entry->lng}}&hl=fa;z=14&amp"
							style="color:#0000FF;text-align:left"
							target="_blank"
						>
							نمایش در صفحه دیگر
						</a>
						</small>
				</div>
                {{-- <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
                    </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                    </div> --}}
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
                    <div class="accordion" id="accordionExample">
                        @foreach ($crud->entry->checklists()->with("questions","questions.question")->get() as $key=>$checklist)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{$key}}">
                                <button class="accordion-button {{$key == 0 ? '':'collapsed'}}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                    {{$checklist->category->name}}
                                </button>
                                </h2>
                                <div id="collapse{{$key}}" class="accordion-collapse collapse {{$key == 0 ? 'show':''}}" aria-labelledby="heading{{$key}}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
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
														<p class="text-center">مشخصات دوربین</p>
														<div class="row">
															<div class="col-lg-4">
																<strong>آی پی دوربین: </strong>
																<span>{{$crud->entry->cctv_ip}}</span>
															</div>
															<div class="col-lg-4">
																<strong>یوزرنیم دوربین: </strong>
																<span>{{$crud->entry->cctv_username}}</span>
															</div>	
															<div class="col-lg-4">
																<strong>پسورد دوربین: </strong>
																<span>{{$crud->entry->cctv_password}}</span>
															</div>	
															<div class="col-lg-4">
																<strong>پورت دوربین: </strong>
																<span>{{$crud->entry->cctv_port}}</span>
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
                    @if ($crud->entry->attachments)
                        <h6 class="mt-4 mb-2">فایل های ضمیمه</h6>
                        <div class="row">
                            @foreach ($crud->entry->attachments as $item)
                                <div class="col-lg-3 my-2">
                                    @if (strpos($item,".png"))


                                        <a data-lightbox="attachments" href="/storage/{{$item}}">
                                            <img style="max-width: 100%;height:200px" src="/storage/{{$item}}" alt="">
                                        </a>
                                    @else
                                        <a download="{{basename($item)}}" href="/storage/{{$item}}">مشاهده و دانلود</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
            </div>
        </div>
	</div>
	</div>
</div>
@endsection
@push('after_scripts')


@endpush
