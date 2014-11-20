<?php

class CatalogAttributeMeta extends BaseModel {

	protected $guarded = array();

	public $table = 'catalog_attributes_meta';

    protected $fillable = array(
        'category_id',
        'language',
        'active',
        'name',
        'settings',
    );

	public static $rules = array(
        'category_id' => 'required',
        'language' => 'required',
	);

}