<!-- select2 -->
@php
    $current_value = old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' ));
    $field['allows_null'] = $field['allows_null'] ?? $crud->model::isColumnNullable($field['name']);
    if (!isset($field['options'])) {
        $field['options'] = $field['model']::all();
    } else {
        $field['options'] = call_user_func($field['options'], $field['model']::query());
    }

    //build option keys array to use with Select All in javascript.
    $model_instance = new $field['model'];
    $options_ids_array = $field['options']->pluck($model_instance->getKeyName())->toArray();
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    @php
        $model = new $field['model'];
        $entity_model = $crud->model;
        $related_model = $crud->getRelationModel($field['entity']);     
        $key_attribute = $model->getKeyName();
        $identifiable_attributes = [];
        foreach($field['attribute'] as $attr)array_push($identifiable_attributes,$attr);
        array_push($identifiable_attributes,'courses.course_id');
        array_push($identifiable_attributes,'course_programs.program_id');
        array_push($identifiable_attributes,'courses.'.$field['tooltip']);
        $categories = DB::table('courses')->select($field['group_by_cat'])->get()->unique()->toArray();      
        foreach($categories as $key => $cat){
            $categories[$key] = json_decode(json_encode($categories[$key]),true);
            $categories[$key]['records'] = DB::table('courses')
                                ->join('course_programs', 'courses.course_id', '=', 'course_programs.course_id', 'left outer')
                                ->where($field['group_by_cat'], $cat->{$field['group_by_cat']})
                                ->select($identifiable_attributes)->get()->toArray();            
            foreach($categories[$key]['records'] as $num => $item){
                $categories[$key]['records'][$num] = json_decode(json_encode($categories[$key]['records'][$num]),true);
                $categories[$key]['records'][$num]['val'] = "";
                foreach($field['attribute'] as $attr)$categories[$key]['records'][$num]['val'] .= $categories[$key]['records'][$num][$attr] . " ";
            }
        }
        
    @endphp
    <select
        name="{{ $field['name'] }}[]" 
        style="width: 100%"
        data-init-function="bpFieldInitSelect2MultipleElement"
        data-select-all="{{ var_export($field['select_all'] ?? false)}}"
        data-options-for-js="{{json_encode(array_values($options_ids_array))}}"
        data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
        @include('crud::fields.inc.attributes', ['default_class' =>  'form-control select2_multiple'])
    	multiple>

            @if ($field['allows_null'])
                <option value="">-</option>
            @endif

            @if (isset($field['model']) && isset($field['group_by_cat']))
                @foreach ($categories as $category)
                    <optgroup label="{{ $category[$field['group_by_cat']] }}">
                        @foreach ($category['records'] as $subEntry)
                            <option value="{{ $subEntry['course_id'] }}"
                                    title="{{ $subEntry[$field['tooltip']] }}"
                                @if ($subEntry['program_id'] && $subEntry['program_id'] != "")
                                     selected
                                @endif
                            >{{ $subEntry['val'] }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            @endif
    </select>

    @if(isset($field['select_all']) && $field['select_all'])        
        <a class="btn btn-xs btn-default clear" style="margin-top: 5px;"><i class="la la-times"></i> {{ trans('backpack::crud.clear') }}</a>
    @endif
    
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
        <!-- include select2 css-->
        <link href="{{ asset('packages/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
        @if (app()->getLocale() !== 'en')
        <script src="{{ asset('packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js') }}"></script>
        @endif
        <script>
            function bpFieldInitSelect2MultipleElement(element) {

                var $select_all = element.attr('data-select-all');
                if (!element.hasClass("select2-hidden-accessible"))
                    {
                        var $obj = element.select2({
                            theme: "bootstrap"
                        });

                        //get options ids stored in the field.
                       // var options = JSON.parse(element.attr('data-options-for-js'));

                        if($select_all) {
                            element.parent().find('.clear').on("click", function () {
                                $obj.val([]).trigger("change");
                            });
                            element.parent().find('.select_all').on("click", function () {
                                $obj.val(options).trigger("change");
                            });
                        }
                    }
            }
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}