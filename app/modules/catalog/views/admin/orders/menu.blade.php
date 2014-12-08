<?
    #Helper:dd($dic_id);
    $menus = array();

    $array = array();
    if (isset($root_category) && is_object($root_category) && $root_category->id)
        $array['category'] = $root_category->id;

    $menus[] = array(
        'link' => URL::route('catalog.orders.index', $array),
        'title' => 'Все заказы',
        'class' => 'btn btn-default'
    );

    /*
    if (
        Allow::action($module['group'], 'categories_delete')
        && isset($element) && is_object($element) && $element->id
        && ($element->lft+1 == $element->rgt)
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
    */

    /*
    if  (
        Allow::action($module['group'], 'categories_edit')
        && isset($root_category) && is_object($root_category) && $root_category->id
    ) {
        $current_link_attributes = Helper::multiArrayToAttributes(Input::get('filter'), 'filter');
        $menus[] = array(
            'link' => URL::route('catalog.category.edit', array('id' => $root_category->id) + $current_link_attributes),
            'title' => 'Изменить',
            'class' => 'btn btn-success'
        );
    }
    */

    if  (Allow::action($module['group'], 'orders_create') && 0) {
        #$current_link_attributes = Helper::multiArrayToAttributes(Input::get('filter'), 'filter');
        $array = array();
        $array['category'] = Input::get('category');
        $menus[] = array(
            'link' => URL::route('catalog.orders.create', $array),
            'title' => 'Добавить',
            'class' => 'btn btn-primary'
        );
    }

    #Helper::d($menus);
?>
    
    <h1>
        Заказы
        @if (0)
            @if (isset($element) && is_object($element) && $element->name)

                @if (isset($element->category) && is_object($element->category) && $element->category->name)
                &nbsp;&mdash;&nbsp;
                {{ $element->category->name }}
                @endif

                &nbsp;&mdash;&nbsp;
                {{ $element->name }}

            @elseif (isset($root_category) && is_object($root_category) && $root_category->name)

                &nbsp;&mdash;&nbsp;
                {{ $root_category->name }}

            @endif
        @endif
    </h1>

    {{ Helper::drawmenu($menus) }}
