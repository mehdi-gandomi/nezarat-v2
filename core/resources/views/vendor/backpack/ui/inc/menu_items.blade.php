{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<!--<li class="nav-item"><a class="nav-link" href="{{ backpack_url('home') }}"><i class="la la-home nav-icon"></i> صفحه اصلی</a></li>-->
@if(auth('backpack')->user()->user_type == 4)

<x-backpack::menu-item  title="لیست دفاتر" icon="la la-files-o" :link="backpack_url('office-file')" />
<x-backpack::menu-item  title="سوابق بازرسی" icon="la la-file" :link="backpack_url('inspection-log')" />
<x-backpack::menu-item  title="بازرسی مجدد" icon="la la-files-o" :link="backpack_url('inspection-log-notification')" />

{{--<x-backpack::menu-item :title="trans('backpack::crud.file_manager')" icon="la la-files-o" :link="backpack_url('elfinder')" />--}}
<!-- <x-backpack::menu-item title="تنظیمات" icon='la la-cog' :link="backpack_url('setting')" /> -->

@elseif(auth('backpack')->user()->user_type == 3 || (auth('backpack')->user()->provinces && is_array(auth('backpack')->user()->provinces) && in_array(11,auth('backpack')->user()->provinces)))
<x-backpack::menu-item  title="لیست دفاتر" icon="la la-files-o" :link="backpack_url('office-file')" />

{{--<x-backpack::menu-item :title="trans('backpack::crud.file_manager')" icon="la la-files-o" :link="backpack_url('elfinder')" />--}}
<x-backpack::menu-item  title="سوابق بازرسی" icon="la la-file" :link="backpack_url('inspection-log')" />
<x-backpack::menu-item title="بازرس ها" icon="la la-users" :link="backpack_url('user')" />
<x-backpack::menu-item title="تنظیمات" icon='la la-cog' :link="backpack_url('setting')" />
{{--<x-backpack::menu-dropdown title="Add-ons" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-header title="Authentication" />
    <x-backpack::menu-dropdown-item title="Users" icon="la la-user" :link="backpack_url('user')" />
    <x-backpack::menu-dropdown-item title="Roles" icon="la la-group" :link="backpack_url('role')" />
    <x-backpack::menu-dropdown-item title="Permissions" icon="la la-key" :link="backpack_url('permission')" />
</x-backpack::menu-dropdown>--}}
<!--complaint-->
<x-backpack::menu-item title="{{__('Complaints')}}" icon="la la-question" :link="backpack_url('complaint')" />
<x-backpack::menu-item title="{{__('Report')}}" icon="la la-question" :link="backpack_url('report')" />
<x-backpack::menu-item title="گزارشات دفاتر" icon="la la-question" :link="backpack_url('office-reports')" />
@endif


{{-- <x-backpack::menu-item title="Inspection log employees" icon="la la-question" :link="backpack_url('inspection-log-employee')" /> --}}
{{----}}


<x-backpack::menu-item title="{{__('Office documents')}}" icon="la la-question" :link="backpack_url('office-document')" />
<x-backpack::menu-item title="{{__('Inspection orders')}}" icon="la la-question" :link="backpack_url('inspection-order')" />
