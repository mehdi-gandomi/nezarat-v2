@if ($crud->hasAccess('list'))
    <a href="{{ url('admin/office-file') }}" class="btn btn-secondary" data-style="zoom-in">
        <span><i class="la la-arrow-right"></i> بازگشت به لیست دفاتر</span>
    </a>
@endif
