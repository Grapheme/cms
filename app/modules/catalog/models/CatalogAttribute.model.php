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

    public function values() {
        return $this->hasMany('CatalogAttributeValue', 'attribute_id', 'id');
    }

    public function value() {
        return $this->hasOne('CatalogAttributeValue', 'attribute_id', 'id')
            ->where('language', Config::get('app.locale'))
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

}