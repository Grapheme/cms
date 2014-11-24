<?php

class CatalogAttribute extends BaseModel {

	protected $guarded = array();

	public $table = 'catalog_attributes';

    protected $fillable = array(
        'active',
        'slug',
        'attributes_group_id',
        'type',
        'settings',
        'lft',
        'rgt',
    );

	public static $rules = array(
        #'slug' => 'required',
	);


    public function attributes_group() {
        return $this->belongsTo('CatalogAttributeGroup', 'attributes_group_id', 'id')
            ->orderBy('lft', 'ASC')
            ;
    }

    public function products() {
        return $this->hasMany('CatalogProduct', 'category_id', 'id')
            ->orderBy('lft', 'ASC')
            ;
    }


    /**
    * Связь возвращает все META-данные записи (для всех языков)
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function metas() {
        return $this->hasMany('CatalogAttributeMeta', 'attribute_id', 'id');
    }

    /**
     * Связь возвращает META для записи, для текущего языка запроса
     *
     * @return mixed
     */
    public function meta() {
        return $this->hasOne('CatalogAttributeMeta', 'attribute_id', 'id')
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

                #dd($meta->settings);

                if (isset($meta->settings) && $meta->settings != '' && is_string($meta->settings))
                    $meta->settings = @json_decode($meta->settings, 1);

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

                if (isset($this->meta->settings) && $this->meta->settings != '' && is_string($this->meta->settings))
                    $this->settings = @json_decode($this->meta->settings, 1);
            }

            if ($unset)
                unset($this->meta);
        }

        ## Extract attributes_group
        if (isset($this->attributes_group)) {
            $this->attributes_group = $this->attributes_group->extract($unset);
        }

        return $this;
    }
}