<?php

/**
 *
 * Класс для упрощения работы с каталогом.
 * Предназначен для выполнения базовых действий с объектами каталога.
 * Например: оформление заказа, смена статуса заказа и т.д.
 *
 */
class Catalog extends BaseController {
	
	public function __construct(){
		##
	}


    public static function create_order(array $array) {

        if (!isset($array) || !is_array($array))
            return false;

        /**
         * Создаем заказ
         */
    }

}