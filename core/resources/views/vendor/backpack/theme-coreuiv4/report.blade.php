@extends(backpack_view('blank'))

@section('after_styles')
    <style media="screen">
        .backpack-profile-form .required::after {
            content: ' *';
            color: red;
        }
		/* HTML: <div class="loader"></div> */
.loader {
  width: 60px;
  aspect-ratio: 4;
  --_g: no-repeat radial-gradient(circle closest-side,#000 90%,#0000);
  background: 
    var(--_g) 0%   50%,
    var(--_g) 50%  50%,
    var(--_g) 100% 50%;
  background-size: calc(100%/3) 100%;
  animation: l7 1s infinite linear;
}
@keyframes l7 {
    33%{background-size:calc(100%/3) 0%  ,calc(100%/3) 100%,calc(100%/3) 100%}
    50%{background-size:calc(100%/3) 100%,calc(100%/3) 0%  ,calc(100%/3) 100%}
    66%{background-size:calc(100%/3) 100%,calc(100%/3) 100%,calc(100%/3) 0%  }
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
    <section class="content-header">
        <div class="container-fluid mb-3">
            <h1>{{ trans('Report') }}</h1>
        </div>
    </section>
@endsection

@section('content')
    <div class="row">

        @if (session('success'))
        <div class="col-lg-8">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if ($errors->count())
        <div class="col-lg-8">
            <div class="alert alert-danger">
                <ul class="mb-1">
                    @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
		<form id="reportForm">
			@csrf
			<div class="row">
				<div class="col-lg-4">
					<label>سوالات</label>
					<select name="general_question_id" id="general_question_id" class="form-control">
						
						@foreach($questions as $question)
							<option value="{{$question->id}}">{{$question->short_description}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-4">
					<label>استان</label>
					<select name="province_id" id="province_id" class="form-control">
					<option value="">همه استانها</option>
						@foreach($provinces as $province)
							<option value="{{$province->id}}">{{$province->name}}</option>
						@endforeach
					</select>
				</div>
				<div id="office_code_wrap" class="col-lg-4 d-none">
					<label>دفتر</label>
					<select name="office_code" id="office_code" class="form-control">
					
					
					</select>
				</div>
				<div class="col-lg-4">
					<button type="button" onclick="doExport()" class="btn btn-primary mt-3">خروجی اکسل</button>
				</div>
			</div>
		</form>
		<div class="d-flex d-none justify-content-center my-3" id="loader-wrap">
		<div class="loader"></div>
		</div>
		<div id="chartWrap">
		  <canvas id="myChart"></canvas>
		</div>
     

    </div>
@endsection
@push('after_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
	$(document).ready(function(e){
doReport()
	})
	$("#province_id").on("change",function(){
		$("#office_code_wrap").addClass('d-none')
		fetch("/ajax/offices?province_id="+$(this).val())
			.then(res=>res.json())
			.then(res=>{
				if(res.ok){
					let output="<option value=''>همه دفاتر</option>"+res.data.map(item=>`
						<option value="${item.office_code}">${item.first_name} ${item.last_name} (${item.office_code})</option>
					`).join(" ")
					$("#office_code").html(output)
					$("#office_code_wrap").removeClass('d-none')
					doReport()
				}
			})
	})
  const ctx = document.getElementById('myChart');
  const labels=@json($provinces->pluck('name')->toArray());
  window.chart=new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'گزارش بازرسی',
        data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
  function doExport(){
	fetch('/admin/report/export',{
		method:"post",
		body:new FormData($("#reportForm")[0])
	})
	.then(resp => resp.blob())
	.then(blob => {
		const url = window.URL.createObjectURL(blob);
		const a = document.createElement('a');
		a.style.display = 'none';
		a.href = url;
		// the filename you want
		a.download = 'export.xlsx';
		document.body.appendChild(a);
		a.click();
		window.URL.revokeObjectURL(url);
		
	})
	.catch(() => alert('oh no!'));
  }
  function doReport(){
	  $("#loader-wrap").removeClass("d-none")
fetch('/admin/report', {
			method: 'POST',
			headers: {
			'Accept': 'application/json'
			},
			body: new FormData($("#reportForm")[0])
		}).then(res=>res.json())
		.then(res=>{
			$("#loader-wrap").addClass("d-none")
			if(res.ok){
				window.chart.destroy()
				 window.chart=new Chart(ctx, {
					type: 'bar',
					data: {
					labels: res.data.labels,
					datasets: [{
						label: 'گزارش بازرسی',
						data: res.data.data,
						borderWidth: 1
					}]
					},
					options: {
					scales: {
						y: {
						beginAtZero: true
						}
					}
					}
				});
			}
			console.log(res)
		})
  }
  $("#general_question_id").on("input",function(e){
doReport()
  })
  $("#office_code").on("input",function(e){
doReport()
  })
  $("#reportForm").on("submit",function(e){
	  e.preventDefault();
	   fetch('/admin/report', {
			method: 'POST',
			headers: {
			'Accept': 'application/json'
			},
			body: new FormData($("#reportForm")[0])
		}).then(res=>res.json())
		.then(res=>{
			if(res.ok){
				window.chart.destroy()
				 window.chart=new Chart(ctx, {
					type: 'bar',
					data: {
					labels: res.data.labels,
					datasets: [{
						label: 'گزارش بازرسی',
						data: res.data.data,
						borderWidth: 1
					}]
					},
					options: {
					scales: {
						y: {
						beginAtZero: true
						}
					}
					}
				});
			}
			console.log(res)
		})
		
  })
</script>
@endpush