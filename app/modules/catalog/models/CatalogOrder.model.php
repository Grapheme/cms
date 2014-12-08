<?php

class CatalogOrder extends BaseModel {

	protected $guarded = array();

	public $table = 'catalog_orders';
    protected $softDelete = true;

    protected $fillable = array(
        'status_id',
        'client_id',
        'client_name',
        'delivery_info',
    );

	public static $rules = array(
        #'slug' => 'required',
	);


    public function status() {
        return $this->hasOne('CatalogOrderStatus', 'id', 'status_id');
    }

    public function products() {
        return $this->hasMany('CatalogOrderProduct', 'order_id', 'id');
    }
}