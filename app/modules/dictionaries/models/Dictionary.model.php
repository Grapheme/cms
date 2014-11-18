<?php

class Dictionary extends BaseModel {

	protected $guarded = array();

    public $table = 'dictionary';
    #public $timestamps = false;

	public static $order_by = "name ASC";

    protected $fillable = array(
        'slug',
        'name',
        'entity',
        'icon_class',
        'hide_slug',
        'make_slug_from_name',
        'name_title',
        'pagination',
        'view_access',
        'sort_by',
        'sort_order_reverse',
        'sortable',
        'order',
    );

	public static $rules = array(
		'name' => 'required',
	);


    /**
     * Связь возвращает все записи словаря со всеми полями; с сортировкой.
     *
     * @return mixed
     */
    public function values() {
        return $this->hasMany('DicVal', 'dic_id', 'id')
            ->where('version_of', NULL)
            ->with('meta', 'fields', 'textfields')
            /*
            ->with('meta', array('fields' => function($query){
                #$query->whereIn('name', array_keys((array)Config::get('dic.dic_name.fields')));
            }))
            */
            ->orderBy('order', 'ASC')
            ->orderBy('slug', 'ASC')
            ->orderBy('name', 'ASC')
            ->orderBy('id', 'ASC')
        ;
    }

    /**
     * Связь возвращает все записи словаря со всеми полями, без доп. условия сортировки.
     * Только для внутреннего использования.
     *
     * @return mixed
     */
    public function values_no_conditions() {

        $tbl_dicval = (new DicVal())->getTable();

        return $this->hasMany('DicVal', 'dic_id', 'id')
            ->select($tbl_dicval.'.*')
            ->where('version_of', NULL)
            ->with('meta', 'fields', 'textfields')
            ;
    }

    /**
     * Связь возвращает ОДНУ запись из словаря. Только для использования с доп. условиями.
     *
     * @return mixed
     */
    public function value() {
        return $this->hasOne('DicVal', 'dic_id', 'id')->where('version_of', NULL);
    }

    /**
     * Возвращает количество записей в словаре
     *
     * @return int
     */
    public function values_count() {
        return DicVal::where('dic_id', $this->id)->where('version_of', NULL)->count();
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
     * В функцию передается коллекция объектов, полученная из Eloguent методом ->get(),
     * которая имеет в себе некоторую коллекцию прочих объектов, полученную через связь hasMany (с помощью ->with('...')).
     * Пример: словарь со значениями - Dic::where('slug', 'dicname')->with('values')->get();
     * Функция возвращает массив, ключами которого являются исходные ключи родительской коллекции, а в значение заносится
     * массив, генерирующийся по принципу метода ->lists('name', 'id'), но без дополнительных запросов к БД.
     * Если $listed_key = false, то вместо вложенной коллекции будет перебираться родительская, на предмет поиска соответствий.
     *
     * @param object $collection - Eloquent Collection
     * @param string $listed_key - Key of the child collection, may be false
     * @param string $value
     * @param string $key
     * @return array
     *
     * @author Alexander Zelensky
     */
    public static function makeLists($collection, $listed_key = 'values', $value, $key = '') {
        #Helper::ta($collection);
        #$lists = new Collection;
        $lists = array();
        foreach ($collection as $c => $col) {
            if (!$listed_key) {

                #Helper::d((int)$col->$value);

                if (isset($col->$value))
                    if ($key != '')
                        $lists[$col->$key] = $col->$value;
                    else
                        $lists[] = $col->$value;

            } else {

                $list = array();
                if (isset($col->$listed_key) && count($col->$listed_key))
                    #Helper::ta($col->$listed_key);
                    foreach ($col->$listed_key as $e => $el) {
                        #Helper::d("$e => $el");
                        if ($key != '')
                            $list[$el->$key] = $el->$value;
                        else
                            $list[] = $el->$value;
                    }
                    #Helper::dd($list);
                $lists[$c] = $list;
            }
            #Helper::ta($col);
        }
        #Helper::dd($lists);
        return $lists;
    }


    /**
     * "Ленивая загрузка" данных без использования связи
     *
     * @param $collection
     * @param $key
     * @param $relation_array
     * @return mixed
     */
    public static function custom_load_hasOne($collection, $key, $relation_array, Closure $additional_rules = NULL) {

        $model = $relation_array[0];
        $local_id = $relation_array[1];
        $remote_id = $relation_array[2];

        $list = self::makeLists($collection, null, $local_id);
        #Helper::d($list);

        $values = new Collection;
        if (count($list)) {
            $values = $model::whereIn($remote_id, $list);
            if (is_callable($additional_rules)) {
                #$values = $additional_rules($values);
                /**
                 * Правильный способ применения доп. условий через функцию-замыкание
                 */
                call_user_func($additional_rules, $values);
            }
            $values = $values->get();

            $values = Dic::modifyKeys($values, 'id');
            #Helper::tad($values);
        }

        foreach($collection as $e => $element) {

            if (isset($element->$local_id) && isset($values[$element->$local_id])) {

                /**
                 * Правильная кастомная установка поля.
                 * Доп. поле должно устанавливаться как связь (relation)
                 */
                $element->relations[$key] = @$values[$element->$local_id] ?: NULL;

                /**
                 * Правильное обновление значения элемента коллекции
                 */
                $collection->put($e, $element);
            }
        }

        unset($list);
        unset($values);

        return $collection;
    }


    /**
     * Возвращает все записи в словаре, по его системному имени.
     * Вторым параметром передается функция-замыкание с доп. условиями выборки,
     * аналогичная по синтаксису доп. условия при вызове связи.
     *
     * @param $slug
     * @param callable $conditions
     * @return mixed
     */
    public static function valuesBySlug($slug, Closure $conditions = NULL) {
        #Helper::dd($slug);
        $return = Dic::where('slug', $slug);
        #dd($conditions);
        if (is_callable($conditions))
            $return = $return->with(array('values_no_conditions' => $conditions));
        else
            $return = $return->with('values');

        $return = $return->first();

        #Helper::tad($return);

        if (is_object($return))
            $return = isset($return->values_no_conditions) ? $return->values_no_conditions : $return->values;
        else
            $return = Dic::firstOrNew(array('slug' => $slug, 'version_of' => NULL))->with('values')->first()->values;
        #return self::firstOrNew(array('slug' => $slug))->values;
        return $return;
    }


    /**
     * Возвращает значение записи из словаря по системному имени словаря и системному имени записи.
     * Третьим параметром можно передать метку, указывающую на необходимость сделать экстракт записи.
     *
     * @param $dic_slug
     * @param $val_slug
     * @param bool $extract
     * @return mixed|static
     */
    public static function valueBySlugs($dic_slug, $val_slug, $extract = false) {

        #Helper::d("$dic_slug, $val_slug");

        $data = self::where('slug', $dic_slug)->with(array('value' => function($query) use ($val_slug){
                $query->where('version_of', NULL);
                $query->where('slug', $val_slug);
                $query->with('meta', 'fields', 'seo', 'related_dicvals');
            }))->first()->value;

        if ($extract) {
            $data->extract(0);
        }

        #Helper::tad($data);

        return is_object($data) ? $data : self::firstOrNew(array('id' => 0));
    }


    /**
     * Возвращает значение записи из словаря по системному имени словаря и ID записи.
     * Третьим параметром можно передать метку, указывающую на необходимость сделать экстракт записи.
     *
     * @param $dic_slug
     * @param $val_id
     * @param bool $extract
     * @return mixed|static
     */
    public static function valueBySlugAndId($dic_slug, $val_id, $extract = false) {

        $data = self::where('slug', $dic_slug)->with(array('value' => function($query) use ($val_id){
            $query->where('version_of', NULL);
            $query->where('id', $val_id);
            $query->with('meta', 'fields', 'seo', 'related_dicvals');
        }))
            ->first()
            ->value
        ;
        #Helper::tad($data);

        if ($extract)
            $data->extract(0);
        #Helper::tad($data);

        return is_object($data) ? $data : self::firstOrNew(array('id' => 0));
    }

    /**
     * Возвращает записи из словаря по системному имени словаря и набору IDs нужных записей.
     * Третьим параметром можно передать метку, указывающую на необходимость сделать экстракт каждой записи.
     *
     * @param $dic_slug
     * @param $val_ids
     * @param bool $extract
     * @return mixed|static
     */
    public static function valuesBySlugAndIds($dic_slug, $val_ids, $extract = false) {

        $data = self::where('slug', $dic_slug)->with(array('values_no_conditions' => function($query) use ($val_ids){
                $query->where('version_of', NULL);
                $query->whereIn('id', $val_ids);
                $query->with('meta', 'fields', 'seo', 'related_dicvals');
            }))
            ->first()
            ->values_no_conditions
        ;
        #Helper::tad($data);

        ## Need to test
        if ($extract)
            $data = DicVal::extracts($data);
        #Helper::tad($data);

        return is_object($data) ? $data : self::firstOrNew(array('id' => 0));
    }




    /**
     * DEPRECATED
     * Устаревшие и не рекомендуемые к использованию методы
     */
    public static function whereSlugValues($slug) {
        return self::firstOrNew(array('slug' => $slug))->values;
    }


}

class Dic extends Dictionary {
    ## Alias
}