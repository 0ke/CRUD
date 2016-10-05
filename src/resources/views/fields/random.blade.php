<!-- random input -->

<?php
    //Pattern Combinations
    $alphanumericsymbols_mixed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@$%^*()-+[];,.?';
    $alphanumericsymbols_lower = 'abcdefghijklmnopqrstuvwxyz0123456789@$%^*()-+[];,.?';
    $alphanumericsymbols_upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@$%^*()-+[];,.?';
    $alphanumeric_mixed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $alphanumeric_lower = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $alphanumeric_upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $alpha_mixed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $alpha_lower = 'abcdefghijklmnopqrstuvwxyz';
    $alpha_upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $symbols = '@$%^*()-+[];,.?';
    $numeric = '0123456789';

    //Casing configuration
    if( isset($field['random_casing']) ){
        $case = strtolower($field['random_casing']);
    } else {
        $case = 'mixed';
    }

    //Length configuration
    if( isset($field['random_length']) ){
        $length = $field['random_length'];
    } else {
        $length = 16;
    }

    //Pattern configuration
    if( isset($field['random_pattern']) ){
        $pattern_type = strtolower($field['random_pattern']);
        switch($pattern_type){
            case 'numeric':
            case 'number':
                $combination = $numeric;
                break;
            case 'alpha':
            case 'letters':
                if( $case == 'upper' ){
                    $combination = $alpha_upper;
                } elseif( $case == 'lower' ){
                    $combination = $alpha_lower;
                } else {
                    $combination = $alpha_mixed;
                }
                break;
            case 'alphanumeric':
            case 'lettersnumbers':
                if( $case == 'upper' ){
                    $combination = $alphanumeric_upper;
                } elseif( $case == 'lower' ){
                    $combination = $alphanumeric_lower;
                } else {
                    $combination = $alphanumeric_mixed;
                }
                break;
            case 'alphanumericsymbols':
            case 'lettersnumberssymbols':
                if( $case == 'upper' ){
                    $combination = $alphanumericsymbols_upper;
                } elseif( $case == 'lower' ){
                    $combination = $alphanumericsymbols_lower;
                } else {
                    $combination = $alphanumericsymbols_mixed;
                }
                break;
            case 'symbols':
            case 'symbol':
                $combination = $symbols;
                break;
            default:
                $combination = $field['random_pattern'];
                break;
        }
    } else {
        $combination = $alphanumericsymbols_mixed;
    }

    $random_config = ['pattern' => $combination, 'length' => $length];
?>

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>

    <div class="row random-row">
        <div class="col-xs-10">
            <input
                data-random="{{ json_encode($random_config) }}"
                type="text"
                name="{{ $field['name'] }}"
                value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
                @include('crud::inc.field_attributes')
            >
        </div>
        <div class="col-xs-2">
            <button type="button" class="btn btn-primary btn-sm pull-right">Randomise</button>
        </div>
    </div>
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')

    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
    <script src="https://cdn.jsdelivr.net/places.js/1/places.min.js"></script>
    <script>
        jQuery(document).ready(function($){
            $('[data-random]').each(function(){
                $field = $(this),
                $button = $field.parents('.random-row').find('button'),
                $config = $field.data('random');

                $button.on('click', function(){
                    var string = '';
                    var possible = $config.pattern;

                    for( var i = 0; i < $config.length; i++ ){
                        string += possible.charAt(Math.floor(Math.random() * possible.length));
                    };

                    $field.val( string );
                });
            })
        });
    </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
