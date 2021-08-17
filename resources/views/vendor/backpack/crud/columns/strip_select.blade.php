{{-- single relationships (1-1, 1-n) --}}
@php
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['limit'] = 200; //this was not enough to prevent the truncation
    $column['attribute'] = $column['attribute'] ?? (new $column['model'])->identifiableAttribute();

    $attributes = $crud->getRelatedEntriesAttributes($entry, $column['entity'], $column['attribute']);
    foreach ($attributes as $key => &$text) {
        $text = $text; 
    }
@endphp

<span>
    @if(count($attributes))
        {{ $column['prefix'] }}
        @foreach($attributes as $key => $text)
            @php
                $related_key = $key;                 
                $textstripped = strip_tags($text);               
            @endphp
           
            <span class="d-inline-flex">
                @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
                    @if($column['escaped'])
                        {{ $textstripped }}
                    @else
                        {!! $textstripped !!}
                    @endif
                @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')

                @if(!$loop->last), @endif
            </span>
        @endforeach
        {{ $column['suffix'] }}
    @else
        -
    @endif
</span>