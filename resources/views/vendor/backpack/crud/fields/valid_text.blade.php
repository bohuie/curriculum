<!-- text input and runs basic validation on html inputs(if req=true)-->
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-prepend"><span class="input-group-text">{!! $field['prefix'] !!}</span></div> @endif
        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::fields.inc.attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-append"><span class="input-group-text">{!! $field['suffix'] !!}</span></div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        {{-- YOUR JS HERE --}}
        <script type="text/javascript" src="{{ asset('packages/jquery-ui-dist/jquery-ui.min.js') }}"></script>
        <script>
        $(document).ready(function(){           
            $(document).on('submit', 'form', function(e){
                let stopSubmit = false;
                let eleList = Array.prototype.slice.call(document.querySelectorAll('tr[class=array-row] td input[treq=true]'));
                let eL2 = Array.prototype.slice.call(document.querySelectorAll('textarea[req=true], input[req=true]'));
                eleList.push.apply(eleList,eL2);
                eleList.forEach((ele) => {
                    if((ele.type != "checkbox") && ele.value.length == 0){                        
                        stopSubmit = true;                        
                    }
                });                
                if(stopSubmit){
                    e.preventDefault(); 
                    alert("one or more fields requires input before submission");
                    document.querySelector('button[type=submit]').disabled = false;
                }
            })
        });
        </script>
    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
