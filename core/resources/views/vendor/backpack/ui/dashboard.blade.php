@extends(backpack_view('blank'))

@php

@endphp
<style>
 .menu-card{
            color: #000;
        }
		.menu-card img{
            width:60px;
        }
		.menu-card:hover{
			text-decoration:none;
			color:#000;
		}
	.logo-container{
		display:flex;
		justify-content:center;
		align-items:center;
	}
	.logo-container img{
		/* width:50%; */
		max-height:250px;
	}
	@media(max-width:768px){
		.logo-container img{
			/* width:80%; */
		}
	}
</style>
@section('content')
<div class="logo-container">
<img src="/assets/images/logo-black.png"  />
</div>
<div class="row mt-4">
        <div class="col-lg-3 col-6">
            <a href="{{backpack_url('office-file')}}" class="menu-card">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="{{asset('assets/images/pishkhanlicense.png')}}" class="d-block m-auto" alt="">
                        <p class="mt-2">لیست دفاتر</p>
                    </div>
                </div>
            </a>
        </div>
		 <div class="col-lg-3 col-6">
            <a href="{{backpack_url('inspection-log')}}" class="menu-card">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="{{asset('assets/images/madarek.png')}}" class="d-block m-auto" alt="">
                        <p class="mt-2">سوابق بازرسی</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-6">
            <a href="{{backpack_url('inspection-log-notification')}}" class="menu-card">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="{{asset('assets/images/peygiri.png')}}" class="d-block m-auto" alt="">
                        <p class="mt-2">بازرسی مجدد</p>
                    </div>
                </div>
            </a>
        </div>
		 @if(auth('backpack')->user()->user_type ==3 || in_array(11,auth('backpack')->user()->provinces))
			<div class="col-lg-3 col-6">
			<!--complaint-->
				<a href="{{backpack_url('')}}" class="menu-card">
					<div class="card text-center">
						<div class="card-body">
							<img src="{{asset('assets/images/samanetaamolinam.png')}}" class="d-block m-auto" alt="">
							<p class="mt-2">شکایات</p>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-3 col-6">
				<a href="{{backpack_url('report')}}" class="menu-card">
					<div class="card text-center">
						<div class="card-body">
							<img src="{{asset('assets/images/pishkhanlicense.png')}}" class="d-block m-auto" alt="">
							<p class="mt-2">گزارش</p>
						</div>
					</div>
				</a>
			</div>
		 @endif
    </div>
@endsection