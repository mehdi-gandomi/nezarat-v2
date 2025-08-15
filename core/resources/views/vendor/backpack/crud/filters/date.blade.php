{{-- Date Range Backpack CRUD filter --}}
<li filter-name="{{ $filter->name }}"
    filter-type="{{ $filter->type }}"
    filter-key="{{ $filter->key }}"
	class="nav-item dropdown {{ Request::get($filter->name)?'active':'' }}">
	<a href="#" class="nav-link dropdown-toggle" data-coreui-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
		{{ $filter->label }}
		@if ($filter->currentValue)
			<span class="badge badge-primary" style="color: #000">{{ verta($filter->currentValue)->formatJalaliDate() }}</span>
		@endif
		<span class="caret"></span>
	</a>

	<div class="dropdown-menu p-0">
		<div class="form-group backpack-filter mb-0">
			<div class="input-group date">
		        <div class="input-group-prepend">
		          <span class="input-group-text"><i class="la la-calendar"></i></span>
		        </div>
		        <input class="form-control pull-right"
		        		id="datepicker-{{ $filter->key }}"
		        		type="text"
						placeholder="انتخاب تاریخ"
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
		.nav-link .badge {
			margin-left: 5px;
			font-size: 0.75em;
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
			$('#datepicker-{{ $filter->key }}').removeAttr("name")
			$('#datepicker-{{ $filter->key }}').after( `<input type="hidden" id="datepicker-{{ $filter->key }}_alt" name="{{ $filter->name }}" />` );

			// Convert Gregorian date to Persian for display
			function gregorianToPersian(gregorianDate) {
				if (!gregorianDate) return '';
				try {
					var parts = gregorianDate.split('-');
					var g = new Date(parseInt(parts[0]), parseInt(parts[1])-1, parseInt(parts[2]));
					// Use simple Persian date conversion or return the original date
					return gregorianDate; // For now, return the original date
				} catch(e) {
					return gregorianDate;
				}
			}

			// Set initial Persian date if current value exists
			@if ($filter->currentValue)
				var persianDate = gregorianToPersian('{{ $filter->currentValue }}');
				$('#datepicker-{{ $filter->key }}').val(persianDate);

				// Also update the hidden field with the Gregorian value
				$("#datepicker-{{ $filter->key }}_alt").val('{{ $filter->currentValue }}');

				// Update the filter label to show active state
				$('li[filter-key={{ $filter->key }}]').addClass('active');
			@endif

			var datepicker=$('#datepicker-{{ $filter->key }}').pDatepicker({
					autoClose: true,
					format:"YYYY/MM/DD",
					altField:"#datepicker-{{ $filter->key }}_alt",
					onSelect:(unixDate)=>{

						const d=new Date(unixDate)
						const value=`${d.getFullYear()}-${("0" + (d.getMonth()+1)).slice(-2) }-${d.getDate()}`;

						// Get the Persian date from the datepicker input
						const persianDisplay = $('#datepicker-{{ $filter->key }}').val();

						// Update the filter label with selected date
						var filterLink = $('li[filter-key={{ $filter->key }}] .nav-link');
						var existingBadge = filterLink.find('.badge');

						// Remove existing badge if it exists
						if (existingBadge.length) {
							existingBadge.remove();
						}

						// Add new badge with the selected date
						filterLink.append('<span class="badge badge-primary" style="color: #000">' + persianDisplay + '</span>');

						// Add active class
						$('li[filter-key={{ $filter->key }}]').addClass('active');

						var parameter = '{{ $filter->name }}';

						// Update the hidden field value
						$("#datepicker-{{ $filter->key }}_alt").val(value);

						// Try Backpack's built-in filter submission first
						if (typeof crud !== 'undefined' && typeof crud.submitFilters === 'function') {
							crud.submitFilters();
							return;
						}

						// behaviour for ajax table
						var ajax_table = $('#crudTable').DataTable();
						var current_url = ajax_table.ajax.url();
						var new_url = addOrUpdateUriParameter(current_url, parameter, value);

						// replace the datatables ajax url with new_url and reload it
						new_url = normalizeAmpersand(new_url.toString());

						// Force reload the table with new parameters
						ajax_table.ajax.url(new_url).load(function(json) {
							// Callback after table reload
							console.log('Table reloaded with date filter:', value);
						});

						// add filter to URL
						crud.updateUrl(new_url);

						// mark this filter as active in the navbar-filters
						if (URI(new_url).hasQuery('{{ $filter->name }}', true)) {
							$('li[filter-key={{ $filter->key }}]').removeClass('active').addClass('active');
						}


					},
                    calendar:{
        persian: {
            leapYearMode: 'astronomical'
        }
    },
					altFieldFormatter:(unixDate)=>{
						const d=new Date(unixDate)
						const value=`${d.getFullYear()}-${("0" + (d.getMonth()+1)).slice(-2) }-${d.getDate()}`;
						return value
					}
			});

			$('li[filter-key={{ $filter->key }}]').on('filter:clear', function(e) {
				$('li[filter-key={{ $filter->key }}]').removeClass('active');
				$('#datepicker-{{ $filter->key }}').val("")
				$("#datepicker-{{ $filter->key }}_alt").val("")

				// Remove the badge from filter label
				$('li[filter-key={{ $filter->key }}] .nav-link .badge').remove();
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
