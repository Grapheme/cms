<?php

class CatalogOrder extends BaseModel {

	protected $guarded = array();

	public $table = 'catalog_orders';

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
        return $this->hasOne('CatalogOrderStatus', 'status_id', 'id');
    }
}