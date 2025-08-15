@php
	if(request('province_id')){
		$province=json_decode(request('province_id'),true);

		$citites=\App\Models\City::whereIn('province_id',$province)->get();
	}
@endphp
{{-- Select2 Multiple Backpack CRUD filter --}}
<li filter-name="{{ $filter->name }}"
    filter-type="{{ $filter->type }}"
    filter-key="{{ $filter->key }}"
	class="nav-item dropdown {{ Request::get($filter->name)?'active':'' }}">
    <a href="#" class="nav-link dropdown-toggle" data-coreui-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        {{ $filter->label }}
        @if($filter->isActive() && json_decode($filter->currentValue))
            <span class="badge bg-primary ms-1">{{ count(json_decode($filter->currentValue)) }}</span>
        @endif
        <span class="caret"></span>
    </a>
    <div class="dropdown-menu p-0">
      <div class="form-group backpack-filter mb-0">
            @if($filter->isActive() && json_decode($filter->currentValue))
                <div class="selected-badges p-2 border-bottom">
                    @foreach(json_decode($filter->currentValue) as $selectedKey)
                        @if(isset($citites) && $citites->where('id', $selectedKey)->first())
                            <span class="badge bg-info me-1 mb-1" data-value="{{ $selectedKey }}">
                                {{ $citites->where('id', $selectedKey)->first()->name }}
                                <i class="la la-times remove-badge" style="cursor: pointer; margin-left: 5px;"></i>
                            </span>
                        @endif
                    @endforeach
                </div>
            @endif
			<select
				id="filter_{{ $filter->key }}"
				name="filter_{{ $filter->key }}"
				class="form-control input-sm select2"
				placeholder="{{ $filter->placeholder }}"
				data-filter-key="{{ $filter->key }}"
				data-filter-type="select2_multiple"
				data-filter-name="{{ $filter->name }}"
				data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
				multiple
				>
				@if(isset($citites) && $citites)
					@foreach($citites as $city)
						<option value="{{$city->id}}">{{$city->name}}</option>
					@endforeach
				@endif
			</select>
		</div>
    </div>
  </li>

{{-- ########################################### --}}
{{-- Extra CSS and JS for this particular filter --}}

{{-- FILTERS EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

@push('crud_list_styles')
    <!-- include select2 css-->
    <link href="{{ asset('packages/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
	  .form-inline .select2-container {
	    display: inline-block;
	  }
	  .select2-drop-active {
	  	border:none;
	  }
	  .select2-container .select2-choices .select2-search-field input, .select2-container .select2-choice, .select2-container .select2-choices {
	  	border: none;
	  }
	  .select2-container-active .select2-choice {
	  	border: none;
	  	box-shadow: none;
	  }
	  .select2-container--bootstrap .select2-dropdown {
	  	margin-top: -2px;
	  	margin-left: -1px;
	  }
	  .select2-container--bootstrap {
	  	position: relative!important;
	  	top: 0px!important;
	  }
    </style>
@endpush


{{-- FILTERS EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_list_scripts')
	<!-- include select2 js-->
    <script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
    @if (app()->getLocale() !== 'en')
    <script src="{{ asset('packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js') }}"></script>
    @endif

    <script>
        jQuery(document).ready(function($) {
			  // trigger select2 for each untriggered select2 box
            $('select[name=filter_{{ $filter->key }}]').not('[data-filter-enabled]').each(function () {
            	var filterName = $(this).attr('data-filter-name');
                var filter_key = $(this).attr('data-filter-key');

                $(this).select2({
                	allowClear: true,
					closeOnSelect: false,
					theme: "bootstrap",
					dropdownParent: $(this).parent('.form-group'),
	        	    placeholder: $(this).attr('placeholder'),
                });

                $(this).change(function() {
	                var value = '';
	                if (Array.isArray($(this).val())) {
	                    // clean array from undefined, null, "".
	                    var values = $(this).val().filter(function(e){ return e === 0 || e });
	                    // stringify only if values is not empty. otherwise it will be '[]'.
	                    value = values.length ? JSON.stringify(values) : '';
	                }

					var parameter = '{{ $filter->name }}';

			    	// behaviour for ajax table
					var ajax_table = $("#crudTable").DataTable();
					var current_url = ajax_table.ajax.url();
					var new_url = addOrUpdateUriParameter(current_url, parameter, value);

					// replace the datatables ajax url with new_url and reload it
					new_url = normalizeAmpersand(new_url.toString());
					ajax_table.ajax.url(new_url).load();

					// add filter to URL
					crud.updateUrl(new_url);

					// mark this filter as active in the navbar-filters
					if (URI(new_url).hasQuery(filterName, true)) {
						$("li[filter-key="+filter_key+"]").addClass('active');
					}
					else
					{
						$("li[filter-key="+filter_key+"]").removeClass("active");
						$("li[filter-key="+filter_key+"]").find('.dropdown-menu').removeClass("show");
					}

                    // Update badges display
                    updateSelectedBadges();
				});

				// when the dropdown is opened, autofocus on the select2
				$("li[filter-key="+filter_key+"]").on('shown.bs.dropdown', function () {
					$('#filter_'+filter_key+'').select2('open');
				});

				// clear filter event (used here and by the Remove all filters button)
				$("li[filter-key="+filter_key+"]").on('filter:clear', function(e) {
					// console.log('select2 filter cleared');
					$("li[filter-key="+filter_key+"]").removeClass('active');
	                $('#filter_'+filter_key).val(null).trigger('change.select2');
                    updateSelectedBadges();
				});

				$("#filter_provinceId").on("change",function(e){
					fetch("{{route('cities')}}?province_id="+$("#filter_provinceId").select2('val').join(','))
						.then(res=>res.json())
						.then(res=>{
							if(res){
								$("#filter_cityId").empty()
								res.forEach(item=>{
									var newOption = new Option(item.name, item.id);
									$("#filter_cityId").append(newOption)
								})
							}
						})
				});

                // Handle badge removal
                $(document).on('click', '.remove-badge', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var badge = $(this).closest('.badge');
                    var valueToRemove = badge.data('value');
                    var currentValues = $('#filter_'+filter_key).val() || [];

                    // Remove the value from the select
                    var newValues = currentValues.filter(function(val) {
                        return val != valueToRemove;
                    });

                    $('#filter_'+filter_key).val(newValues).trigger('change');

                    // Update the filter
                    var value = newValues.length ? JSON.stringify(newValues) : '';
                    var parameter = '{{ $filter->name }}';

                    // behaviour for ajax table
                    var ajax_table = $("#crudTable").DataTable();
                    var current_url = ajax_table.ajax.url();
                    var new_url = addOrUpdateUriParameter(current_url, parameter, value);

                    // replace the datatables ajax url with new_url and reload it
                    new_url = normalizeAmpersand(new_url.toString());
                    ajax_table.ajax.url(new_url).load();

                    // add filter to URL
                    crud.updateUrl(new_url);

                    // Update badges display
                    updateSelectedBadges();
                });
            });

            // Initial update of badges on page load
            updateSelectedBadges();

            // Function to update selected badges display (moved outside the loop for global access)
            function updateSelectedBadges() {
                $('select[name=filter_{{ $filter->key }}]').each(function() {
                    var filter_key = $(this).attr('data-filter-key');
                    var selectedValues = $(this).val() || [];
                    var badgeContainer = $("li[filter-key="+filter_key+"] .selected-badges");

                    // Update badge count in dropdown toggle
                    var badgeCount = $("li[filter-key="+filter_key+"] .dropdown-toggle .badge");
                    if (selectedValues.length > 0) {
                        if (badgeCount.length === 0) {
                            $("li[filter-key="+filter_key+"] .dropdown-toggle").append('<span class="badge bg-primary ms-1">' + selectedValues.length + '</span>');
                        } else {
                            badgeCount.text(selectedValues.length);
                        }
                        $("li[filter-key="+filter_key+"]").addClass('active');
                    } else {
                        badgeCount.remove();
                        $("li[filter-key="+filter_key+"]").removeClass('active');
                    }

                    // Update badges in dropdown
                    if (selectedValues.length > 0) {
                        var badgeHtml = '';
                        selectedValues.forEach(function(value) {
                            var label = $('#filter_'+filter_key + ' option[value="' + value + '"]').text();
                            badgeHtml += '<span class="badge bg-info me-1 mb-1" data-value="' + value + '">' +
                                        label +
                                        '<i class="la la-times remove-badge" style="cursor: pointer; margin-left: 5px;"></i>' +
                                        '</span>';
                        });

                        if (badgeContainer.length === 0) {
                            $("li[filter-key="+filter_key+"] .form-group").prepend('<div class="selected-badges p-2 border-bottom">' + badgeHtml + '</div>');
                        } else {
                            badgeContainer.html(badgeHtml);
                        }
                    } else {
                        badgeContainer.remove();
                    }
                });
            }
		});
	</script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
