{{-- Date Range Backpack CRUD filter --}}
<li filter-name="{{ $filter->name }}"
    filter-type="{{ $filter->type }}"
    filter-key="{{ $filter->key }}"
	class="nav-item dropdown {{ Request::get($filter->name)?'active':'' }}">
	<a href="#" class="nav-link dropdown-toggle" data-coreui-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $filter->label }} <span class="caret"></span></a>
	<div class="dropdown-menu p-0">
		<div class="form-group backpack-filter mb-0">
			<div class="input-group date">
		        <div class="input-group-prepend">
		          <span class="input-group-text"><i class="la la-calendar"></i></span>
		        </div>
		        <input class="form-control pull-right"
		        		id="datepicker-{{ $filter->key }}"
		        		type="text"
						
						@if ($filter->currentValue)
							value="{{ $filter->currentValue }}"
						@endif
		        		>
		        <div class="input-group-append datepicker-{{ $filter->key }}-clear-button">
		          <a class="input-group-text" href=""><i class="la la-times"></i></a>
		        </div>
		    </div>
		</div>
	</div>
</li>

{{-- ########################################### --}}
{{-- Extra CSS and JS for this particular filter --}}

{{-- FILTERS EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

@push('crud_list_styles')
    <link rel="stylesheet" href="{{ asset('assets/css/persian-datepicker.min.css') }}">
	<style>
		.input-group.date {
			width: 320px;
			max-width: 100%;
		}
	</style>
@endpush


{{-- FILTERS EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_list_scripts')
@php $language = $filter->options['language'] ?? \App::getLocale(); @endphp
	<!-- include select2 js-->
	<script src="{{ asset('assets/js/persian-date.min.js') }}"></script>
	<script src="{{ asset('assets/js/persian-datepicker.min.js') }}"></script>
  <script>
		jQuery(document).ready(function($) {

			$('#datepicker-{{ $filter->key }}').attr("type","text")
			//$("#from_date").attr("name","from_display")
			$('#datepicker-{{ $filter->key }}').removeAttr("name")
			$('#datepicker-{{ $filter->key }}').after( `<input type="hidden" id="datepicker-{{ $filter->key }}_alt" name="{{ $filter->name }}" />` );
			var datepicker=$('#datepicker-{{ $filter->key }}').pDatepicker({
					autoClose: true,
					format:"YYYY/MM/DD",
					altField:"#datepicker-{{ $filter->key }}_alt",
					onSelect:(unixDate)=>{
						const d=new Date(unixDate)
						const value=`${d.getFullYear()}-${("0" + (d.getMonth()+1)).slice(-2) }-${d.getDate()}`;
							var parameter = '{{ $filter->name }}';

							// behaviour for ajax table
							var ajax_table = $('#crudTable').DataTable();
							var current_url = ajax_table.ajax.url();
							var new_url = addOrUpdateUriParameter(current_url, parameter, value);

							// replace the datatables ajax url with new_url and reload it
							new_url = normalizeAmpersand(new_url.toString());
							ajax_table.ajax.url(new_url).load();

							// add filter to URL
							crud.updateUrl(new_url);

							// mark this filter as active in the navbar-filters
							if (URI(new_url).hasQuery('{{ $filter->name }}', true)) {
								$('li[filter-key={{ $filter->key }}]').removeClass('active').addClass('active');
							}
					},
					altFieldFormatter:(unixDate)=>{
const d=new Date(unixDate)
						const value=`${d.getFullYear()}-${("0" + (d.getMonth()+1)).slice(-2) }-${d.getDate()}`;
						return value
					}
			});   
		
			// var dateInput = $('#datepicker-{{ $filter->key }}').datepicker({
			// 	autoclose: true,
			// 	format: 'yyyy-mm-dd',
			// 	todayHighlight: true,
			// 	language: '{{ $language }}',
			// })
			// .on('changeDate', function(e) {
			// 	var d = new Date(e.date);
			// 	// console.log(e);
			// 	// console.log(d);
			// 	if (isNaN(d.getFullYear())) {
			// 		var value = '';
			// 	} else {
			// 		var value = d.getFullYear() + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + ("0" + d.getDate()).slice(-2);
			// 	}

			// 	var parameter = '{{ $filter->name }}';

		    // 	// behaviour for ajax table
			// 	var ajax_table = $('#crudTable').DataTable();
			// 	var current_url = ajax_table.ajax.url();
			// 	var new_url = addOrUpdateUriParameter(current_url, parameter, value);

			// 	// replace the datatables ajax url with new_url and reload it
			// 	new_url = normalizeAmpersand(new_url.toString());
			// 	ajax_table.ajax.url(new_url).load();

			// 	// add filter to URL
			// 	crud.updateUrl(new_url);

			// 	// mark this filter as active in the navbar-filters
			// 	if (URI(new_url).hasQuery('{{ $filter->name }}', true)) {
			// 		$('li[filter-key={{ $filter->key }}]').removeClass('active').addClass('active');
			// 	}
			// });

			$('li[filter-key={{ $filter->key }}]').on('filter:clear', function(e) {
				// console.log('date filter cleared');
				$('li[filter-key={{ $filter->key }}]').removeClass('active');
				$('#datepicker-{{ $filter->key }}').val("")
				$("#datepicker-{{ $filter->key }}_alt").val("")
			});

			// datepicker clear button
			$(".datepicker-{{ $filter->key }}-clear-button").click(function(e) {
				e.preventDefault();

				$('li[filter-key={{ $filter->key }}]').trigger('filter:clear');
			})
		});
  </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
