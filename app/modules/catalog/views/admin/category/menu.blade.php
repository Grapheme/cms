<?
    #Helper:dd($dic_id);
    $menus = array();
    $menus[] = array(
        'link' => URL::route('catalog.category.index'),
        'title' => 'Категории',
        'class' => 'btn btn-default'
    );
    if (
        Allow::action($module['group'], 'categories_delete') && isset($element) && is_object($element) && $element->id
    ) {
        $menus[] = array(
            'link' => URL::route('catalog.category.destroy', array($element->id)),
            'title' => '<i class="fa fa-trash-o"></i>',
            'class' => 'btn btn-danger remove-category-record',
            'others' => [
                'data-goto' => URL::route('catalog.category.index'),
                'title' => 'Удалить запись'
            ]
        );
    }

    if  (Allow::action($module['group'], 'categories_create')) {
        $current_link_attributes = Helper::multiArrayToAttributes(Input::get('filter'), 'filter');
        $menus[] = array(
            'link' => URL::route('catalog.category.create', $current_link_attributes),
            'title' => 'Добавить',
            'class' => 'btn btn-primary'
        );
    }
    /*
    if (isset($element) && is_object($element) && $element->name && 0) {
        $menus[] = array(
            'link' => action(is_numeric($dic_id) ? 'dicval.edit' : 'entity.edit', array('dic_id' => $dic_id, $element->id)),
            'title' => "&laquo;" . $element->name . "&raquo;",
            'class' => 'btn btn-default'
        );
    }
    if  (
        Allow::action($module['group'], 'dicval_create')
        && (!isset($dic_settings['max_elements']) || !$dic_settings['max_elements'] || $dic_settings['max_elements'] > @$total_elements_current_selection)
    ) {
        $current_link_attributes = Helper::multiArrayToAttributes(Input::get('filter'), 'filter');
        $menus[] = array(
            'link' => action(is_numeric($dic_id) ? 'dicval.create' : 'entity.create', array('dic_id' => $dic_id) + $current_link_attributes),
            'title' => 'Добавить',
            'class' => 'btn btn-primary'
        );
    }
    if (Allow::action($module['group'], 'import')) {
        $menus[] = array(
            'link' => action('dic.import', array('dic_id' => $dic_id)),
            'title' => 'Импорт',
            'class' => 'btn btn-primary'
        );
    }
    if (Allow::action($module['group'], 'edit') && (!$dic->entity || Allow::superuser())) {
        $menus[] = array(
            'link' => action('dic.edit', array('dic_id' => $dic->id)),
            'title' => 'Изменить',
            'class' => 'btn btn-success'
        );
    }

    if (isset($dic_settings['menus']))
        $dic_menu = $dic_settings['menus'];
    #Helper::d($dic_menu);
    if (isset($dic_menu) && is_callable($dic_menu)) {
        $tmp = (array)$dic_menu($dic, isset($element) && is_object($element) ? $element : NULL);
        $menus = array_merge($menus, $tmp);
    }

    */

    #Helper::d($menus);
?>
    
    <h1>
        Категории
        @if (isset($element) && is_object($element) && $element->name)
            &nbsp;&mdash;&nbsp; {{ $element->name }}
        @elseif (isset($root_category) && is_object($root_category) && $root_category->name)
            &nbsp;&mdash;&nbsp; {{ $root_category->name }}
        @endif
    </h1>

    {{ Helper::drawmenu($menus) }}
