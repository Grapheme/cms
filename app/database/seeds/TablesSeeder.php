<?php

class TablesSeeder extends Seeder{

	public function run(){

		#DB::table('settings')->truncate();

        /*
		Setting::create(array(
			'id' => 1,
			'name' => 'language',
			'value' => 'ru',
		));
        */

        Dic::create(array(
            'id' => 1,
            'slug' => 'options',
            'name' => 'Опции',
            'entity' => '1',
            'icon_class' => 'fa-circle-o',
            'name_title' => 'Значение',
            'pagination' => '0',
            'view_access' => '0',
            'sort_by' => 'name',
            'sort_order_reverse' => '0',
            'sortable' => '0',
        ));

        Dic::create(array(
            'id' => 2,
            'slug' => 'news',
            'name' => 'Новости',
            'entity' => '1',
            'icon_class' => 'fa-circle-o',
            'name_title' => NULL,
            'pagination' => '0',
            'view_access' => '0',
            'sort_by' => NULL,
            'sort_order_reverse' => '0',
            'sortable' => '1',
        ));

        /*
        DicVal::inject('room_type', array(
            'slug' => 'first',
            'name' => 'Some name',
            'fields_i18n' => array(
                'ru' => array(
                    'price' => 111,
                    'price2' => 222,
                ),
                'en' => array(
                    'price' => 333,
                    'price2' => 444,
                ),
            ),
        ));
        */

	}
}