<?php

class CatalogProductMeta extends BaseModel {

	protected $guarded = array();

	public $table = 'catalog_products_meta';

    protected $fillable = array(
        'product_id',
        'language',
        'active',
        'name',
        'description',
        'price',
        'settings',
    );

	public static $rules = array(
        'product_id' => 'required',
        'language' => 'required',
	);

}