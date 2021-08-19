<!-- checklist -->
@php
  $model = new $field['model'];
  $key_attribute = $model->getKeyName();
  $identifiable_attributes = [];
  foreach($field['attribute'] as $attr)array_push($identifiable_attributes,$attr);
  array_push($identifiable_attributes,$key_attribute);
 // $identifiable_attributes = rtrim($identifiable_attributes,",");
  // calculate the checklist options
  if (!isset($field['options'])) {
      if(isset($field['category_relation'])){
        $words = explode("-", $field['category_relation']);
        $catTable = array_shift($words);
        
        $field['options']['categories'] = DB::table($catTable)->select($words)->get()->toArray();
        foreach($field['options']['categories'] as $catkey => $cat){
            $field['options']['categories'][$catkey] = $field['model']::where($words[0], $cat->{$words[0]})->select($identifiable_attributes)->get()->toArray();        
        }
      }
      else if(isset($field['category_attribute'])){
        $field['options']['categories'] = $field['model']::select($field['category_attribute'])->distinct()->get()->toArray();
        foreach($field['options']['categories'] as $catkey => $cat){
            $field['options']['categories'][$catkey] = $field['model']::where($field['category_attribute'], $cat)->select($identifiable_attributes)->get()->toArray();        
        }
      }
      else{
        $field['options']['categories'] = $field['model']::select($identifiable_attributes)->get()->toArray();
      }
  } else {
      $field['options']['categories'] = call_user_func($field['options'], $field['model']::query());
  }

  // calculate the value of the hidden input
  $field['value'] = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? [];
  if ($field['value'] instanceof Illuminate\Database\Eloquent\Collection) {
    $field['value'] = $field['value']->pluck($key_attribute)->toArray();
  } elseif (is_string($field['value'])){
    $field['value'] = json_decode($field['value']);
  }

  // define the init-function on the wrapper
  $field['wrapper']['data-init-function'] =  $field['wrapper']['data-init-function'] ?? 'bpFieldInitChecklist';
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <input type="hidden" value='@json($field['value'])' name="{{ $field['name'] }}">

    <div class="row">
        @foreach ($field['options']['categories'] as $catname => $category)
        <div class="category"><h4>{{ $catname }}</h4>
            @foreach ($category as $attr => $option)
                <?php $pKey = "";
                $disp = "";
                foreach($option as $key => $item){
                    if($key == $key_attribute){
                        $pKey = $item;
                        continue;
                    }
                    if(strlen($item) == 7 && $item[0] == "#"){
                        $disp .= "<span style=\"background-color:$item;min-width:25px;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                    }
                    else{ $disp .= "<span>$item</span>&nbsp;&nbsp;"; }
                }
                ?>
                <div class="col-sm-4">
                    <div class="checkbox">
                      <label class="font-weight-normal">
                        <input type="checkbox" value="{{ $pKey }}"> {!! $disp !!}
                      </label>
                    </div>
                </div>
            @endforeach
        </div>
        @endforeach
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
    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script>
            function bpFieldInitChecklist(element) {
                var hidden_input = element.find('input[type=hidden]');
                var selected_options = JSON.parse(hidden_input.val() || '[]');
                var checkboxes = element.find('input[type=checkbox]');
                var container = element.find('.row');

                // set the default checked/unchecked states on checklist options
                checkboxes.each(function(key, option) {
                  var id = $(this).val();

                  if (selected_options.map(String).includes(id)) {
                    $(this).prop('checked', 'checked');
                  } else {
                    $(this).prop('checked', false);
                  }
                });

                // when a checkbox is clicked
                // set the correct value on the hidden input
                checkboxes.click(function() {
                  var newValue = [];

                  checkboxes.each(function() {
                    if ($(this).is(':checked')) {
                      var id = $(this).val();
                      newValue.push(id);
                    }
                  });

                  hidden_input.val(JSON.stringify(newValue));

                });
            }
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}