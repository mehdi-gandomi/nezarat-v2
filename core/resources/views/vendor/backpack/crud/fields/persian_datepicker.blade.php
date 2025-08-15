<!-- persian datepicker input -->

<?php
	// if the column has been cast to Carbon or Date (using attribute casting)
	// get the value as a date string (gregorian)
	if (isset($field['value']) && ($field['value'] instanceof \Carbon\CarbonInterface)) {
		$field['value'] = $field['value']->format('Y-m-d');
	}

	$field['value'] = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';
	$field['attributes']['style'] = $field['attributes']['style'] ?? 'background-color: white!important;';
	$field['attributes']['readonly'] = $field['attributes']['readonly'] ?? 'readonly';

	// generate safe ids for hidden/display inputs
	$safeFieldName = str_replace(['[',']'], '_', $field['name']);
	$hiddenId = $field['attributes']['id'] ?? 'pd_'.$safeFieldName.'_alt';
	$visibleId = 'pd_'.$safeFieldName.'_display';
?>

@include('crud::fields.inc.wrapper_start')
	<input type="hidden" id="{{ $hiddenId }}" class="form-control" name="{{ $field['name'] }}" value="{{ $field['value'] }}">
	<label>{!! $field['label'] !!}</label>
	@include('crud::fields.inc.translatable_icon')
	<div class="input-group date">
		<input
			id="{{ $visibleId }}"
			type="text"
			data-persian-datepicker='{{ isset($field['persian_datepicker_options']) ? json_encode($field['persian_datepicker_options']) : '{}' }}'
			data-init-function="bpFieldInitPersianDatepickerElement"
			@include('crud::fields.inc.attributes')
			>
		<div class="input-group-append">
			<span class="input-group-text">
				<span class="la la-calendar"></span>
			</span>
		</div>
	</div>

	{{-- HINT --}}
	@if (isset($field['hint']))
		<p class="help-block">{!! $field['hint'] !!}</p>
	@endif
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
	@php
		$crud->markFieldTypeAsLoaded($field);
	@endphp

	{{-- FIELD CSS - will be loaded in the after_styles section --}}
	@push('crud_fields_styles')
	<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css">
	@endpush

	{{-- FIELD JS - will be loaded in the after_scripts section --}}
	@push('crud_fields_scripts')
	<script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
	<script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>
	<script>
		function bpFieldInitPersianDatepickerElement(element) {
			var $fake = element,
				$field = $fake.closest('.input-group').parent().find('input[type="hidden"]'),
				customOptions = $.extend({
					format: 'YYYY/MM/DD',
					autoClose: true,
					initialValue: false,
					altField: '#' + $field.attr('id'),
                    calendar:{
        persian: {
            leapYearMode: 'astronomical'
        }
    },
					altFieldFormatter: function (unixDate) {
						var d = new Date(unixDate);
						var month = ('0' + (d.getMonth()+1)).slice(-2);
						var day = ('0' + d.getDate()).slice(-2);
						return d.getFullYear() + '-' + month + '-' + day;
					}
				}, $fake.data('persian-datepicker') || {});

			var picker = $fake.pDatepicker(customOptions);

			var existingVal = $field.val();
			if (existingVal) {
				try {
					var parts = existingVal.split('-');
					var g = new Date(parseInt(parts[0],10), parseInt(parts[1],10)-1, parseInt(parts[2],10));
					// set visible value in Persian calendar
					if (typeof persianDate !== 'undefined') {
						$fake.val(new persianDate(g).format(customOptions.format));
					}
					// update picker state if API available
					if (picker && typeof picker.setDate === 'function') {
						picker.setDate(g.getTime());
					}
				} catch(e){}
			}
		}
	</script>
	@endpush

@endif
{{-- End of Extra CSS and JS --}}


