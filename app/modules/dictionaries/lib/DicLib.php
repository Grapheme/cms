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

        #$return = new Collection;
        $return = [];
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

        #return $return;

        /**
         * Определяем, с чем мы работаем - с Коллекцией или с Пагинатором
         */
        ## Collection / Paginator
        $classname = last(explode('\\', '\\'.get_class($elements)));
        #Helper::tad($classname);
        if ($classname == 'Collection') {

            $elements->__construct($return);

        } elseif ($classname == 'Paginator') {

            $elements->setItems($return);
        }

        return $elements;
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
     * @return Collection
     */
    public static function loadImages($collection, $key = 'image_id', $field = null){

        #Helper::tad($collection);

        if (!is_array($key))
            $key = (array)$key;

        #Helper::ta(get_class($collection));
        #Helper::tad($collection instanceof Collection);

        #Helper::ta((int)($collection instanceof \Illuminate\Pagination\Paginator));
        #dd($collection);
        #var_dump($collection);

        $single_mode = false;
        $paginator_mode = false;

        #die($collection instanceof Collection);

        if ($collection instanceof Collection || $collection instanceof Illuminate\Database\Eloquent\Collection) {

            ## all ok

        } elseif ($collection instanceof \Illuminate\Pagination\Paginator) {

            $paginator_mode = true;
            $paginator = clone $collection;
            $collection = $collection->getItems();

        } else {

            $single_mode = true;
            $temp = $collection;
            $collection = new Collection();
            $collection->put(0, $temp);
        }

        #Helper::tad('single: ' . $single_mode . ', paginator: ' . $paginator_mode . ', key: ' . print_r($key, 1));

        #Helper::tad($collection);
        #dd($collection);

        if (!count($collection) || !count($key))
            return $collection;

        $images_ids_attr = array();
        $images_ids = array();
        /**
         * Перебираем все объекты в коллекции
         */
        foreach ($collection as $obj) {

            /**
             * Если при вызове указано поле (связь) - берем ее вместо текущего объекта
             */
            $work_obj = $field ? $obj->$field : $obj;

            #dd($work_obj->$key[0]);
            #dd($work_obj);
            #Helper::tad($work_obj);

            if (!is_object($work_obj)) {
                dd($work_obj);
            }

            /**
             * Перебираем все переданные ключи с ID изображений
             */
            foreach ($key as $attr) {

                #Helper::ta($attr . ' - ' . is_numeric($work_obj->$attr));

                /**
                 * Собираем ID изображений - в общий список и в список с разбиением по ключу
                 */
                if (is_numeric($work_obj->$attr)) {

                    /**
                     * Собираем ID изображений из обычных полей
                     */
                    $images_ids_attr[$attr][] = $work_obj->$attr;
                    $images_ids[] = $work_obj->$attr;

                } elseif (isset($work_obj->allfields) && count($work_obj->allfields)) {

                    /**
                     * Собираем ID изображений из i18n полей
                     */
                    #Helper::tad($work_obj->allfields);
                    foreach ($work_obj->allfields as $locale_sign => $locale_fields) {

                        foreach ($locale_fields as $locale_key => $locale_value) {

                            if ($locale_key == $attr) {

                                #Helper::tad($locale_key . ' == ' . $attr);

                                /**
                                 * Work good
                                 */
                                $images_ids[] = $locale_value;
                            }
                            #Helper::tad($locale_key);
                        }
                    }
                }
            }

        }
        #Helper::dd($images_ids);
        #Helper::d($images_ids_attr);

        #Helper::ta($images_locale_ids_attr);
        #Helper::tad($images_locale_ids);


        $images = [];
        if (count($images_ids)) {

            $images = Photo::whereIn('id', $images_ids);

            if (NULL != ($db_remember_timeout = Config::get('app.settings.main.db_remember_timeout')) && $db_remember_timeout > 0)
                $images->remember($db_remember_timeout);

            $images = $images->get();
            $images = self::modifyKeys($images, 'id');
            #Helper::tad($images);
        }


        #dd($collection);

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
                foreach ($key as $attr) {

                    if (is_object($work_obj)) {

                        #Helper::tad($work_obj);

                        if (isset($work_obj->$attr) && is_numeric($work_obj->$attr)) {

                            /**
                             * Заменяем ID изображений на объекты в обычных полях
                             */
                            if (@$images[$work_obj->$attr]) {

                                $tmp = $work_obj->$attr;
                                $image = $images[$tmp];

                                $work_obj->setAttribute($attr, $image);
                            }

                        } elseif (isset($work_obj->allfields) && count($work_obj->allfields)) {

                            /**
                             * Заменяем ID изображений на объекты в i18n полях
                             */
                            #Helper::tad($work_obj->allfields);
                            $temp = $work_obj->allfields;
                            foreach ($work_obj->allfields as $locale_sign => $locale_fields) {

                                foreach ($locale_fields as $locale_key => $locale_value) {

                                    if ($locale_key == $attr) {

                                        #Helper::tad($locale_key . ' == ' . $attr);
                                        if (@$images[$temp[$locale_sign][$locale_key]])
                                            $temp[$locale_sign][$locale_key] = $images[$temp[$locale_sign][$locale_key]];
                                    }
                                    #Helper::tad($locale_key);
                                }
                            }
                            $work_obj->allfields = $temp;
                        }
                    }
                }

                if ($field) {
                    $obj->$field = $work_obj;
                    #} else {
                    #    $obj = $work_obj;
                }

                if (is_object($collection))
                    $collection->put($o, $obj);
                else
                    $collection[$o] = $obj;
            }
        }

        #dd($single_mode);

        if ($paginator_mode) {

            $paginator->setItems($collection);
            $collection = $paginator;

        } else if ($single_mode) {

            $collection = $collection[0];
        }

        #Helper::tad($collection);
        #dd($collection);
        #var_dump($collection);
        #Helper::ta('<hr/>');

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

        #Helper::ta(get_class($collection));
        #Helper::tad($collection instanceof Collection);

        #Helper::ta((int)($collection instanceof \Illuminate\Pagination\Paginator));
        #dd($collection);

        $single_mode = false;
        $paginator_mode = false;

        if ($collection instanceof Collection) {

            ## all ok

        } elseif ($collection instanceof \Illuminate\Pagination\Paginator) {

            $paginator_mode = true;
            $paginator = clone $collection;
            $collection = $collection->getItems();

        } else {

            $single_mode = true;
            $temp = $collection;
            $collection = new Collection();
            $collection->put(0, $temp);
        }

        #Helper::tad($collection);
        #dd($collection);

        if (!count($collection) || !count($key))
            return $collection;

        $ids = array();
        /**
         * Перебираем все объекты в коллекции
         */
        foreach ($collection as $obj) {

            /**
             * Если при вызове указано поле (связь) - берем ее вместо текущего объекта
             */
            $work_obj = $field ? $obj->$field : $obj;

            #Helper::ta($work_obj);
            #continue;
            #dd($work_obj);

            /**
             * Перебираем все переданные ключи с ID
             */
            foreach ($key as $attr) {

                #var_dump($work_obj);
                #continue;

                if (is_object($work_obj) && is_numeric($work_obj->$attr)) {

                    /**
                     * Собираем ID - в общий список и в список с разбиением по ключу
                     */
                    $ids_attr[$attr][] = $work_obj->$attr;
                    $ids[] = $work_obj->$attr;
                }
            }
        }
        #Helper::dd($images_ids);
        #Helper::d($images_ids_attr);
        #die;

        $objects = [];
        if (count($ids)) {

            $objects = Gallery::whereIn('id', $ids)->with('photos');

            if (NULL != ($db_remember_timeout = Config::get('app.settings.main.db_remember_timeout')) && $db_remember_timeout > 0)
                $objects->remember($db_remember_timeout);

            $objects = $objects->get();
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
                foreach ($key as $attr) {

                    if (is_numeric($work_obj->$attr)) {

                        if (@$objects[$work_obj->$attr]) {

                            $tmp = $work_obj->$attr;
                            $image = $objects[$tmp];

                            $work_obj->setAttribute($attr, $image);
                        }
                    }

                }

                if ($field) {
                    $obj->$field = $work_obj;
                }

                #$collection->relations[$o] = $obj;
                if (is_object($collection))
                    $collection->put($o, $obj);
                else
                    $collection[$o] = $obj;
            }
        }

        if ($paginator_mode) {

            $paginator->setItems($collection);
            $collection = $paginator;

        } else if ($single_mode)
            $collection = $collection[0];

        #Helper::tad($collection);
        #dd($collection);

        return $collection;
    }


    /**
     * С помощью данного метода можно подгрузить загруженные файлы (Upload) к элементам коллекции по их ID, хранящемся в поле
     * В качестве третьего параметра можно передать название поля элемента коллекции, например связи один-ко-многим.
     *
     * Пример вызова:
     * $specials = DicLib::loadUploads($specials, ['upload_id']);
     *
     * @param $collection
     * @param string $key
     * @param string/null $field
     * @return bool
     */
    public static function loadUploads($collection, $key = 'upload_id', $field = null){

        #Helper::tad($collection);

        if (!is_array($key))
            $key = (array)$key;

        #Helper::ta(get_class($collection));
        #Helper::tad($collection instanceof Collection);

        #Helper::ta((int)($collection instanceof \Illuminate\Pagination\Paginator));
        #dd($collection);
        #var_dump($collection);

        $single_mode = false;
        $paginator_mode = false;

        #die($collection instanceof Collection);

        if ($collection instanceof Collection || $collection instanceof Illuminate\Database\Eloquent\Collection) {

            ## all ok

        } elseif ($collection instanceof \Illuminate\Pagination\Paginator) {

            $paginator_mode = true;
            $paginator = clone $collection;
            $collection = $collection->getItems();

        } else {

            $single_mode = true;
            $temp = $collection;
            $collection = new Collection();
            $collection->put(0, $temp);
        }

        #Helper::tad('single: ' . $single_mode . ', paginator: ' . $paginator_mode . ', key: ' . print_r($key, 1));

        #Helper::tad($collection);
        #dd($collection);

        if (!count($collection) || !count($key))
            return $collection;

        $upload_ids = array();
        /**
         * Перебираем все объекты в коллекции
         */
        foreach ($collection as $obj) {

            /**
             * Если при вызове указано поле (связь) - берем ее вместо текущего объекта
             */
            $work_obj = $field ? $obj->$field : $obj;

            #dd($work_obj->$key[0]);
            #dd($work_obj);

            /**
             * Перебираем все переданные ключи с ID изображений
             */
            foreach ($key as $attr) {

                #Helper::ta($attr . ' - ' . is_numeric($work_obj->$attr));

                if (!is_object($work_obj)) {
                    dd($work_obj);
                }

                if (is_numeric($work_obj->$attr)) {

                    /**
                     * Собираем ID изображений - в общий список и в список с разбиением по ключу
                     */
                    $upload_ids_attr[$attr][] = $work_obj->$attr;
                    $upload_ids[] = $work_obj->$attr;
                }
            }

        }
        #Helper::dd($upload_ids);
        #Helper::d($upload_ids_attr);


        $images = [];
        $uploads = [];
        if (count($upload_ids)) {

            $uploads = Upload::whereIn('id', $upload_ids)->get();
            $uploads = self::modifyKeys($uploads, 'id');
            #Helper::tad($uploads);
        }

        #dd($collection);

        if (count($uploads)) {

            /**
             * Перебираем все объекты в коллекции
             */
            foreach ($collection as $o => $obj) {

                /**
                 * Если при вызове указано поле (связь) - берем ее вместо текущего объекта
                 */
                $work_obj = $field ? $obj->$field : $obj;

                /**
                 * Перебираем все переданные ключи с ID файлов
                 */
                foreach ($key as $attr) {

                    if (is_object($work_obj) && is_numeric($work_obj->$attr)) {

                        if (@$uploads[$work_obj->$attr]) {

                            $tmp = $work_obj->$attr;
                            $upload = $uploads[$tmp];

                            $work_obj->setAttribute($attr, $upload);
                        }
                    }
                }

                if ($field) {
                    $obj->$field = $work_obj;
                    #} else {
                    #    $obj = $work_obj;
                }

                if (is_object($collection))
                    $collection->put($o, $obj);
                else
                    $collection[$o] = $obj;
            }
        }

        #dd($single_mode);

        if ($paginator_mode) {

            $paginator->setItems($collection);
            $collection = $paginator;

        } else if ($single_mode) {

            $collection = $collection[0];
        }

        #Helper::tad($collection);
        #dd($collection);
        #var_dump($collection);
        #Helper::ta('<hr/>');

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


    public static function nestedModelToTree($categories, $indent_string = '&nbsp; &nbsp; &nbsp; ', $debug = false) {

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
            $categories_for_select[$category->id] = str_repeat($indent_string, $category->indent) . $category->name;
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
        if (count($values)) {
            foreach ($values as $v => $value) {
                if (is_object($value) && isset($value->$key)) {
                    if (!isset($return[$value->$key]))
                        $return[$value->$key] = new Collection();
                    $return[$value->$key][$v] = $value;
                }
            }
        }
        return $return;
    }


    public static function make2levelListForSelect($parents, $parent_field = 'name', $childs, $child_parent_field_id, $child_field_key = 'id', $child_field_value = 'name') {

        $select = [];
        foreach ($parents as $p => $parent) {
            if (!isset($select[$parent->$parent_field]))
                $select[$parent->$parent_field] = [];
        }
        #Helper::tad($select);

        foreach ($childs as $c => $child) {

            if (!isset($child->$child_parent_field_id))
                continue;

            $parent_tmp = $parents[$child->$child_parent_field_id];
            if (@is_array($select[$parent_tmp->$parent_field]))
                $select[$parent_tmp->$parent_field][$child->$child_field_key] = $child->$child_field_value;
        }
        #Helper::tad($select);

        return $select;
    }

}