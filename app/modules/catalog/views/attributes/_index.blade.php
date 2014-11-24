
{{ Helper::ta_($attribute) }}

@include($module['gtpl'] . 'attributes.' . $attribute->type)

@if (0)
@if ($attribute->type == 'text')
    @include($module['gtpl'] . 'attributes.text')
@elseif ($attribute->type == 'textarea')
    @include($module['gtpl'] . 'attributes.textarea')
@elseif ($attribute->type == 'wysiwyg')
    @include($module['gtpl'] . 'attributes.wysiwyg')
@elseif ($attribute->type == 'checkbox')
    @include($module['gtpl'] . 'attributes.checkbox')
@elseif ($attribute->type == 'select')
    @include($module['gtpl'] . 'attributes.select')
@endif
@endif