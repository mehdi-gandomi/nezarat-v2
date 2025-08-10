{{-- regular object attribute --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
	
    $column['escaped'] = $column['escaped'] ?? true;
    $column['limit'] = $column['limit'] ?? 32;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['text'] = $column['default'] ?? '-';

    if(is_array($column['value'])) {
        $provinces=\App\Models\Province::whereIn("id",$column['value'])->pluck('name')->implode(',');
		$column['value']=$provinces;
	}else if($column['value']){
		$province=\App\Models\Province::where("id",$column['value'])->first();
		$column['value']=optional($province)->name;
	}

@endphp

<span>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
        @if($column['escaped'])
            {{ $column['value'] }}
        @else
            {!! $column['value'] !!}
        @endif
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>
