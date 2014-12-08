<?php

class CatalogOrderProductAttribute extends BaseModel {

	protected $guarded = array();

	public $table = 'catalog_orders_products_attributes';

    protected $fillable = array(
        'product_id',
        'attribute_id',
        'value',
    );

	public static $rules = array(
        #'slug' => 'required',
	);


    public function product() {
        return $this->belongsTo('CatalogProduct', 'status_id', 'id');
    }

    public function attribute() {
        return $this->belongsTo('CatalogOrderProductAttribute', 'attribute_id', 'id');
    }
}