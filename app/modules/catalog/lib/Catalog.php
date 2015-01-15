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
        $order = new CatalogOrder();


        /**
         * Имя заказчика и адрес доставки
         */
        if (isset($array['client_name']) && $array['client_name'] != '') {
            $order->client_name = $array['client_name'];
            $order->save();
        }
        if (isset($array['delivery_info']) && $array['delivery_info'] != '') {
            $order->delivery_info = $array['delivery_info'];
            $order->save();
        }

        /**
         * Сохраняем заказ
         */
        $order->save();


        /**
         * Обновляем статус заказа
         */
        if (isset($array['status'])) {

            if (
                is_numeric($array['status'])
                && (int)$array['status'] > 0
            ) {
                /**
                 * Ищем новый статус в БД
                 */
                $new_status = CatalogOrderStatus::where('id', (int)$array['status'])
                    ->with('meta')
                    ->first()
                ;

                if (is_object($new_status) && isset($new_status->meta) && is_object($new_status->meta)) {

                    /**
                     * Добавляем новый статус в историю
                     */
                    $record = new CatalogOrderStatusHistory();
                    $record->order_id = $order->id;
                    $record->status_id = (int)$array['status'];
                    $record->comment = @$array['status_comment'] ?: NULL;
                    $record->changer_id = @$array['changer_id'] ?: NULL;
                    $record->changer_name = @$array['changer_name'] ?: NULL;
                    $record->status_cache = $new_status->meta->toJson();
                    $record->save();

                    /**
                     * Обновляем статус заказа
                     */
                    $order->status_id = $record->status_id;
                    $order->save();
                }
            }
        }


        /**
         * Добавляем в заказ продукты
         */
        if (isset($array['products']) && is_array($array['products']) && count($array['products'])) {

            $total_sum = 0;

            /**
             * Перебираем все переданные товары
             */
            foreach ($array['products'] as $order_product_id => $order_product) {

                /**
                 * Если не указаны кол-во или цена - пропускаем товар
                 */
                if (
                    !isset($order_product['count'])
                    || !is_numeric($order_product['count'])
                    || $order_product['count'] <= 0

                    || !isset($order_product['price'])
                    || !is_numeric($order_product['price'])
                    || $order_product['price'] <= 0
                )
                    continue;

                $product = new CatalogOrderProduct();
                $product->count = $order_product['count'];
                $product->price = $order_product['price'];
                $product->save();

                /**
                 * Добавляем цену позиции к общей сумме
                 */
                $total_sum += abs($order_product['count'] * $order_product['price']);


                /**
                 * Сохраняем атрибуты товара
                 */
                if (isset($order_product['attributes']) && is_array($order_product['attributes']) && count($order_product['attributes'])) {

                    /**
                     * Перебираем все атрибуты товара
                     */
                    foreach ($order_product['attributes'] as $attribute_id => $attribute_value) {

                        if (!is_numeric($attribute_id))
                            continue;

                        /**
                         * Ищем атрибут в БД
                         */
                        $attribute = (new CatalogAttribute())
                            ->with('meta')
                            ->where('id', $attribute_id)
                            ->first()
                        ;

                        if (!is_object($attribute) || !is_object($attribute->meta))
                            continue;

                        /**
                         * Сохраняем атрибут
                         */
                        $order_product_attribute = new CatalogOrderProductAttribute();
                        $order_product_attribute->order_id = $order->id;
                        $order_product_attribute->product_id = $product->id;
                        $order_product_attribute->attribute_id = $attribute->id;
                        $order_product_attribute->attribute_cache = $attribute->meta->name;
                        $order_product_attribute->value = $attribute_value;
                        $order_product_attribute->save();
                    }
                }
            }

            /**
             * Обновляем общую сумму заказа
             */
            if ($order->total_sum != $total_sum) {
                $order->total_sum = $total_sum;
                $order->save();
            }
        }

        #Helper::tad($order);
        return $order;
    }


    public static function update_order($order_id = 0, array $array) {

        if (!$order_id || !isset($array) || !is_array($array))
            return false;

        /**
         * Ищем заказ
         */
        $order = (new CatalogOrder())
            ->where('id', $order_id)
            ->withTrashed()
            ->first()
        ;

        if (!is_object($order))
            return false;

        if ($order->deleted_at) {
            $order->restore();
        }

        /**
         * Обновляем статус заказа
         */
        if (isset($array['status'])) {

            $order->load('status.meta');
            $order->extract(1);

            #Helper::tad($array['status']);

            if (
                is_numeric($array['status'])
                && (int)$array['status'] > 0
                && (!isset($order->status) || !is_object($order->status) || $order->status->id != (int)$array['status'])
            ) {
                /**
                 * Ищем новый статус в БД
                 */
                $new_status = CatalogOrderStatus::where('id', (int)$array['status'])
                    ->with('meta')
                    ->first()
                ;

                if (is_object($new_status) && isset($new_status->meta) && is_object($new_status->meta)) {

                    /**
                     * Добавляем новый статус в историю
                     */
                    $record = new CatalogOrderStatusHistory();
                    $record->order_id = $order->id;
                    $record->status_id = (int)$array['status'];
                    $record->comment = @$array['status_comment'] ?: NULL;
                    $record->changer_id = @$array['changer_id'] ?: NULL;
                    $record->changer_name = @$array['changer_name'] ?: NULL;
                    $record->status_cache = $new_status->meta->toJson();
                    $record->save();

                    /**
                     * Обновляем статус заказа
                     */
                    $order->status_id = $record->status_id;
                    $order->save();
                }
            }
        }


        /**
         * Имя заказчика и адрес доставки
         */
        if (isset($array['client_name']) && $array['client_name'] != '') {
            $order->client_name = $array['client_name'];
            $order->save();
        }
        if (isset($array['delivery_info']) && $array['delivery_info'] != '') {
            $order->delivery_info = $array['delivery_info'];
            $order->save();
        }


        /**
         * Обновляем продукты. Удаляем из сохраненного заказа те продукты, которых нет в данных, пришедших из формы!
         */
        if (isset($array['products']) && is_array($array['products'])) {

            /**
             * Загружаем продукты
             */
            $order->load('products');
            $order->extract(1);

            $total_sum = 0;

            /**
             * Перебираем все товары, уже добавленные к заказу
             */
            foreach ($order->products as $order_product_id => $order_product) {

                /**
                 * Если информации о товаре нет в переданном массиве - удаляем его из заказа
                 */
                if (!isset($array['products'][$order_product_id])) {
                    $order_product->delete();
                    unset($order->products[$order_product_id]);
                    continue;
                }

                $need_to_save = false;

                /**
                 * Товар - количество
                 */
                if (
                    isset($array['products'][$order_product_id]['count'])
                    && is_numeric($array['products'][$order_product_id]['count'])
                    && $order_product->count != $array['products'][$order_product_id]['count']
                ) {
                    $order_product->count = $array['products'][$order_product_id]['count'];
                    $order->products[$order_product_id] = $order_product;
                    $need_to_save = true;
                }

                /**
                 * Товар - цена
                 */
                if (
                    isset($array['products'][$order_product_id]['price'])
                    && is_numeric($array['products'][$order_product_id]['price'])
                    && $order_product->price != $array['products'][$order_product_id]['price']
                ) {
                    $order_product->price = $array['products'][$order_product_id]['price'];
                    $order->products[$order_product_id] = $order_product;
                    $need_to_save = true;
                }

                /**
                 * Добавляем цену позиции к общей сумме
                 */
                $total_sum += abs($order_product->count * $order_product->price);

                /**
                 * Обновляем продукт, если это требуется
                 */
                if ($need_to_save)
                    $order_product->save();
            }

            /**
             * Обновляем общую сумму заказа
             */
            if ($order->total_sum != $total_sum) {
                $order->total_sum = $total_sum;
                $order->save();
            }

            #Helper::tad($order->products);
        }

        #Helper::tad($order);
        return $order;
    }


    public static function delete_order($order_id = 0) {

        if (!$order_id)
            return false;

        /**
         * "Удаляем" заказ
         */
        $order = CatalogOrder::find($order_id);
        if (!is_object($order))
            return false;

        $order->delete();
        return true;
    }

}