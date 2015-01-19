<?php

/**
 * Class DicLib
 * Класс для работы с модулем словарей
 */
class DicLib extends BaseController {
	
	public function __construct(){
		##
	}

    /**
     * Экстрактит все записи коллекции
     *
     * $collection = DicLib::extracts($collection, null, true, true);
     *
     * @param $elements
     * @param string $field
     * @param bool $unset
     * @param bool $extract_ids
     * @return Collection
     */
    public static function extracts($elements, $field = null, $unset = false, $extract_ids = true) {

        $return = new Collection;
        #Helper::dd($return);
        foreach ($elements as $e => $element) {

            #Helper::ta($e);
            #Helper::ta($element);
            #dd($element);

            if (isset($field) && $field != '') {

                $el = is_object($element) ? @$element->$field : @$element[$field];

                if (is_object($el)) {
                    $el->extract($unset);
                }
                if (is_object($element)) {
                    $element->$field = $el;
                } else {
                    $element[$field] = $el;
                }

            } else {

                $element->extract($unset);
            }

            $return[($extract_ids ? $element->id : $e)] = $element;
        }
        return $return;
    }

    /**
     * В функцию передается коллекция объектов, полученная из Eloguent методом ->get(),
     * а также название поля, значение которого будет установлено в качестве ключа для каждого элемента коллекции.
     *
     * @param object $collection - Eloquent Collection
     * @param string $key
     * @return object
     *
     * @author Alexander Zelensky
     */
    public static function modifyKeys($collection, $key = 'slug') {
        #Helper::tad($collection);
        #$array = array();
        $array = new Collection;
        foreach ($collection as $c => $col) {
            $current_key = is_object($col) ? $col->$key : @$col[$key];
            if (NULL !== $current_key) {
                $array[$current_key] = $col;
            }
        }
        return $array;
    }


    /**
     * С помощью данного метода можно подгрузить изображения (Photo) к элементам коллекции по их ID, хранящемся в поле
     * В качестве третьего параметра можно передать название поля элемента коллекции, например связи один-ко-многим.
     *
     * Пример вызова:
     * $specials = DicLib::loadImages($specials, ['special_photo', 'special_plan']);
     *
     * @param $collection
     * @param string $key
     * @param string/null $field
     * @return bool
     */
    public static function loadImages($collection, $key = 'image_id', $field = null){

        if (!is_array($key))
            $key = (array)$key;

        if (get_class($collection) == 'DicVal') {

            $temp = $collection;

            $collection = new Collection();
            $collection->put(0, $temp);
        }

        #Helper::dd($collection);

        if (!count($collection) || !count($key))
            return false;

        $images_ids = array();
        /**
         * Перебираем все объекты в коллекции
         */
        foreach ($collection as $obj) {

            /**
             * Если при вызове указано поле (связь) - берем ее вместо текущего объекта
             */
            $work_obj = $field ? $obj->$field : $obj;

            #Helper::dd($work_obj);

            /**
             * Перебираем все переданные ключи с ID изображений
             */
            foreach ($key as $attr)
                if (is_numeric($work_obj->$attr)) {

                    /**
                     * Собираем ID изображений - в общий список и в список с разбиением по ключу
                     */
                    $images_ids_attr[$attr][] = $work_obj->$attr;
                    $images_ids[] = $work_obj->$attr;
                }
        }
        #Helper::dd($images_ids);
        #Helper::d($images_ids_attr);


        $images = [];
        if (count($images_ids)) {

            $images = Photo::whereIn('id', $images_ids)->get();
            $images = self::modifyKeys($images, 'id');
            #Helper::tad($images);
        }


        if (count($images)) {

            /**
             * Перебираем все объекты в коллекции
             */
            foreach ($collection as $o => $obj) {

                /**
                 * Если при вызове указано поле (связь) - берем ее вместо текущего объекта
                 */
                $work_obj = $field ? $obj->$field : $obj;

                /**
                 * Перебираем все переданные ключи с ID изображений
                 */
                foreach ($key as $attr)
                    if (is_numeric($work_obj->$attr)) {

                        if (@$images[$work_obj->$attr]) {

                            $tmp = $work_obj->$attr;
                            $image = $images[$tmp];

                            $work_obj->setAttribute($attr, $image);
                        }
                    }

                if ($field) {
                    $obj->$field = $work_obj;
                }

                $collection->put($o, $obj);
            }
        }

        return $collection;

    }



    /**
     * С помощью данного метода можно подгрузить галереи (Gallery) к элементам коллекции по их ID, хранящемся в поле
     * В качестве третьего параметра можно передать название поля элемента коллекции, например связи один-ко-многим.
     *
     * Пример вызова:
     * $specials = DicLib::loadImages($specials, ['special_photo', 'special_plan']);
     *
     * @param $collection
     * @param string $key
     * @param string/null $field
     * @return bool
     */
    public static function loadGallery($collection, $key = 'gallery_id', $field = null){

        if (!is_array($key))
            $key = (array)$key;

        if (get_class($collection) == 'DicVal') {

            $temp = $collection;

            $collection = new Collection();
            $collection->put(0, $temp);
        }

        #Helper::dd($collection);

        if (!count($collection) || !count($key))
            return false;

        $ids = array();
        /**
         * Перебираем все объекты в коллекции
         */
        foreach ($collection as $obj) {

            /**
             * Если при вызове указано поле (связь) - берем ее вместо текущего объекта
             */
            $work_obj = $field ? $obj->$field : $obj;

            #Helper::dd($work_obj);

            /**
             * Перебираем все переданные ключи с ID
             */
            foreach ($key as $attr)
                if (is_numeric($work_obj->$attr)) {

                    /**
                     * Собираем ID - в общий список и в список с разбиением по ключу
                     */
                    $ids_attr[$attr][] = $work_obj->$attr;
                    $ids[] = $work_obj->$attr;
                }
        }
        #Helper::dd($images_ids);
        #Helper::d($images_ids_attr);


        $objects = [];
        if (count($ids)) {

            $objects = Gallery::whereIn('id', $ids)->with('photos')->get();
            $objects = self::modifyKeys($objects, 'id');
            #Helper::tad($objects);
        }


        if (count($objects)) {

            /**
             * Перебираем все объекты в коллекции
             */
            foreach ($collection as $o => $obj) {

                /**
                 * Если при вызове указано поле (связь) - берем ее вместо текущего объекта
                 */
                $work_obj = $field ? $obj->$field : $obj;

                /**
                 * Перебираем все переданные ключи с ID изображений
                 */
                foreach ($key as $attr)
                    if (is_numeric($work_obj->$attr)) {

                        if (@$objects[$work_obj->$attr]) {

                            $tmp = $work_obj->$attr;
                            $image = $objects[$tmp];

                            $work_obj->setAttribute($attr, $image);
                        }
                    }

                if ($field) {
                    $obj->$field = $work_obj;
                }

                #$collection->relations[$o] = $obj;
                $collection->put($o, $obj);
            }
        }

        return $collection;

    }


    /**
     * Функция для вывода выпадающего списка в верхнем меню для фильтрации результатов
     *
     * @param $filter_name
     * @param $filter_default_text
     * @param $filter_dic_elements - array like: array('_id_of_the_dicval_' => '_name_of_the_dicval_')
     * @param $dic
     * @param bool $dicval
     * @return array
     */
    public static function getDicValMenuDropdown($filter_name, $filter_default_text, $filter_dic_elements, $dic, $dicval = false) {

        $filter = Input::get('filter.fields');
        #Helper::d($filter);
        #Helper::ta($dic);

        $dic_id = $dic->entity ? $dic->slug : $dic->id;
        $route = $dic->entity ? 'entity.index' : 'dicval.index';

        ## Get dimensional array for filtration from multidimensional array (Input::get()) #NOSQL
        $current_link_attributes = Helper::multiArrayToAttributes(Input::get('filter'), 'filter');

        ## Main element of the drop-down menu
        if (@$filter[$filter_name]) {

            ## Get current dicval from array of the gettin' filter_dic_elements #NOSQL
            $current_dicval = @$filter_dic_elements[$filter[$filter_name]];

            ## Get all current link attributes & modify for next url generation
            $array = $current_link_attributes;
            $array["filter[fields][{$filter_name}]"] = @$filter[$filter_name];
            $array = (array)$dic_id + $array;

            $parent = array(
                'link' => URL::route($route, $array),
                'title' => $current_dicval,
                'class' => 'btn btn-default',
            );
        } else {

            ## Get all current link attributes & modify for next url generation
            $array = $current_link_attributes;
            unset($array["filter[fields][{$filter_name}]"]);
            $array = (array)$dic_id + $array;

            $parent = array(
                'link' => URL::route($route, $array),
                'title' => $filter_default_text,
                'class' => 'btn btn-default',
            );
        }
        ## Child elements
        $product_types = array();
        if (@$filter[$filter_name]) {

            ## Get all current link attributes & modify for next url generation
            $array = $current_link_attributes;
            unset($array["filter[fields][{$filter_name}]"]);
            $array = (array)$dic_id + $array;

            $product_types[] = array(
                'link' => URL::route($route, $array),
                'title' => $filter_default_text,
                'class' => '',
            );
        }
        foreach ($filter_dic_elements as $element_id => $element_name) {

            if ($element_id == @$filter[$filter_name]) {
                continue;
            }

            ## Get all current link attributes & modify for next url generation
            $array = $current_link_attributes;
            $array["filter[fields][{$filter_name}]"] = $element_id;
            $array = (array)$dic_id + $array;

            $product_types[] = array(
                'link' => URL::route($route, $array),
                'title' => $element_name,
                'class' => '',
            );
        }
        ## Assembly
        $parent['child'] = $product_types;
        return $parent;
    }


    public static function nestedModelToTree($categories, $debug = false) {

        /**
         * Подсчитаем отступ для каждой категории
         */
        $indent_debug = $debug;
        $indent = 0;
        $last_indent_increate_rgt = array();
        foreach ($categories as $category) {

            if ($indent_debug)
                Helper::ta($category);

            $category->indent = $indent;

            if ($indent_debug)
                Helper::d("Устанавливаем текущий отступ категории: " . $indent);

            if ($category->lft+1 < $category->rgt) {

                ++$indent;
                $last_indent_increate_rgt[] = $category->rgt;

                if ($indent_debug) {
                    Helper::d("Увеличиваем текущий уровень отступа: " . $indent . " (" . $category->lft . "+1 < " . $category->rgt . ")");
                    Helper::d("Добавляем RGT в массив 'RGT родительских категории': " . $category->rgt . " => " . implode(', ', $last_indent_increate_rgt));
                }
            }

            #/*

            $plus = 1;
            $exit = false;
            do {
                if (in_array(($category->lft+(++$plus)), $last_indent_increate_rgt)) {

                    --$indent;

                    /*
                    Helper::d("LFT категории + " . $plus . " (" . ($category->lft+$plus) . ") найдено в массиве 'RGT родительских категорий' => " . implode(', ', $last_indent_increate_rgt));
                    Helper::d("Уменьшаем текущий уровень отступа: " . $indent);
                    #*/

                } else {
                    $exit = true;
                }

            } while(!$exit);

            #Helper::d("<hr/>");
        }

        #Helper::tad($categories);

        /**
         * Соберем все категории в массив с отступами для select
         */
        $categories_for_select = array();
        foreach ($categories as $category) {
            $categories_for_select[$category->id] = str_repeat('&nbsp; &nbsp; &nbsp; ', $category->indent) . $category->name;
        }
        if ($indent_debug)
            Helper::dd($categories_for_select);

        return $categories_for_select;
    }


    /**
     * Добавляем недостающие данные для связи многи-ко-многим между записями словаря
     *
     * @param $value
     * @param $field
     * @param $dicval_parent_dic_id
     * @param $dicval_child_dic_id
     * @return array
     */
    public static function formatDicValRel($values, $dicval_parent_field, $dicval_parent_dic_id, $dicval_child_dic_id) {

        $temp = (array)$values;
        $values = array();
        foreach ($temp as $tmp) {
            $values[$tmp] = array(
                'dicval_parent_dic_id' => $dicval_parent_dic_id,
                'dicval_child_dic_id' => $dicval_child_dic_id,
                'dicval_parent_field' => $dicval_parent_field,
            );
        }
        return $values;
    }


    public static function groupByField($values, $key) {

        $return = new Collection();
        foreach ($values as $value) {
            if (is_object($value) && isset($value->$key)) {
                if (!isset($return[$value->$key]))
                    $return[$value->$key] = new Collection();
                $return[$value->$key][] = $value;
            }
        }
        return $return;
    }

}