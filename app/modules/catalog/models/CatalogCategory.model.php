<?php

class CatalogCategory extends BaseModel {

	protected $guarded = array();

	public $table = 'catalog_categories';

    protected $fillable = array(
        'active',
        'slug',
        'image_id',
        'settings',
        'lft',
        'rgt',
    );

	public static $rules = array(
        #'slug' => 'required',
	);


    public function products() {
        return $this->hasMany('CatalogProduct', 'category_id', 'id')
            ->orderBy('lft', 'ASC')
            ;
    }

    public function attributes_groups() {
        return $this->hasMany('CatalogAttributeGroup', 'category_id', 'id')
            ->orderBy('lft', 'ASC')
            ;
    }


    /**
    * Связь возвращает все META-данные записи (для всех языков)
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function metas() {
        return $this->hasMany('CatalogCategoryMeta', 'category_id', 'id');
    }

    /**
     * Связь возвращает META для записи, для текущего языка запроса
     *
     * @return mixed
     */
    public function meta() {
        return $this->hasOne('CatalogCategoryMeta', 'category_id', 'id')
            ->where('language', Config::get('app.locale'))
            ;
    }

    /**
     * Возвращает SEO-данные записи, для текущего языка запроса
     *
     * @return mixed
     */
    public function seo() {
        return $this->hasOne('Seo', 'unit_id', 'id')
            ->where('module', 'CatalogCategory')
            ->where('language', Config::get('app.locale'))
            ;
    }

    /**
     * Связь возвращает все SEO-данные записи, для каждого из языков
     *
     * @return mixed
     */
    public function seos() {
        return $this->hasMany('Seo', 'unit_id', 'id')
            ->where('module', 'CatalogCategory')
            ;
    }

    /**
     * Экстрактит категорию
     *
     * $value->extract();
     *
     * @param bool $unset
     * @return $this
     */
    public function extract($unset = false) {

        #Helper::ta($this);

        ## Extract metas
        if (isset($this->metas)) {
            foreach ($this->metas as $m => $meta) {
                $this->metas[$meta->language] = $meta;
                if ($m != $meta->language || $m === 0)
                    unset($this->metas[$m]);
            }
        }

        ## Extract meta
        if (isset($this->meta)) {

            if (
                is_object($this->meta)
                && ($this->meta->language == Config::get('app.locale') || $this->meta->language == NULL)
            ) {
                if ($this->meta->name != '')
                    $this->name = $this->meta->name;

            }

            #Helper::dd($this);
            if ($unset) {
                unset($this->relations['meta']);
            }
        }

        ## Extract SEOs
        if (isset($this->seos)) {
            #Helper::tad($this->seos);
            if (count($this->seos) == 1 && count(Config::get('app.locales')) == 1) {
                $app_locales = Config::get('app.locales');
                foreach ($app_locales as $locale_sign => $locale_name)
                    break;
                foreach ($this->seos as $s => $seo) {
                    $this->seos[$locale_sign] = $seo;
                    break;
                }
                unset($this->seos[0]);
                #Helper::tad($this->seos);
            } else {
                foreach ($this->seos as $s => $seo) {
                    $this->seos[$seo->language] = $seo;
                    #Helper::d($s . " != " . $seo->language);
                    if ($s != $seo->language || $s === 0)
                        unset($this->seos[$s]);
                }
            }
        }

        ## Extract attributes_groups
        if (isset($this->attributes_groups) && is_object($this->attributes_groups) && count($this->attributes_groups)) {

            #Helper::tad($this->relations['attributes_groups']);

            $attributes_groups = new Collection();
            foreach ($this->relations['attributes_groups'] as $ag => $attributes_group) {

                $temp = $attributes_group->extract($unset);
                #Helper::ta($temp->relations);

                if (is_object($temp) && @count($temp->relations['attributes'])) {

                    $attributes = new Collection();
                    foreach ($temp->relations['attributes'] as $ra => $attribute) {

                        #Helper::ta($attribute);

                        $attribute = $attribute->extract($unset);

                        /**
                         * Правильное обновление значения элемента коллекции
                         */
                        $attributes->put($attribute->slug, $attribute);
                    }
                    $temp->relations['attributes'] = $attributes;
                }
                #Helper::ta($temp->relations);

                /**
                 * Правильное обновление значения элемента коллекции
                 */
                $attributes_groups->put($attributes_group->slug, $temp);
            }
            $this->relations['attributes_groups'] = $attributes_groups;
            #Helper::tad($this->attributes_groups);
        }

        ## Extract attributes count
        $count = 0;
        if (isset($this->attributes_groups) && is_object($this->attributes_groups) && count($this->attributes_groups)) {
            foreach ($this->attributes_groups as $group) {
                #Helper::dd($group->relations['attributes']);
                if (isset($group->relations['attributes']) && is_object($group->relations['attributes']) && count($group->relations['attributes'])) {
                    $count += count($group->relations['attributes']);
                }
            }
        }
        $this->attributes_count = $count;

        return $this;
    }
}