<?php

class CatalogTableSeeder extends Seeder{

	public function run(){

        CatalogCategory::create(array(
            'id' => 1,
            'active' => 1,
            'slug' => 'bicycles',
            'lft' => 1,
            'rgt' => 8,
        ));
        CatalogCategoryMeta::create(array(
            'category_id' => 1,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Велосипеды',
        ));
        CatalogCategoryMeta::create(array(
            'category_id' => 1,
            'language' => 'en',
            'active' => 1,
            'name' => 'Bicycles',
        ));

        CatalogCategory::create(array(
            'id' => 2,
            'active' => 1,
            'slug' => 'mountain',
            'lft' => 2,
            'rgt' => 3,
        ));
        CatalogCategoryMeta::create(array(
            'category_id' => 2,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Горные',
        ));

        CatalogCategory::create(array(
            'id' => 3,
            'active' => 1,
            'slug' => 'road',
            'lft' => 4,
            'rgt' => 5,
        ));
        CatalogCategoryMeta::create(array(
            'category_id' => 3,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Шоссейные',
        ));

        CatalogCategory::create(array(
            'id' => 4,
            'active' => 1,
            'slug' => 'city',
            'lft' => 6,
            'rgt' => 7,
        ));
        CatalogCategoryMeta::create(array(
            'category_id' => 4,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Городские',
        ));

        CatalogCategory::create(array(
            'id' => 5,
            'active' => 1,
            'slug' => 'pc',
            'lft' => 9,
            'rgt' => 10,
        ));
        CatalogCategoryMeta::create(array(
            'category_id' => 5,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Компьютеры',
        ));



        CatalogProduct::create(array(
            'id' => 1,
            'active' => 1,
            'category_id' => 1,
            'slug' => 'normal_bike',
            'article' => 'sku0001',
            'amount' => '5',
            'lft' => 1,
            'rgt' => 2,
        ));
        CatalogProductMeta::create(array(
            'id' => 1,
            'product_id' => 1,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Обычный велосипед',
            'description' => 'Какое-то описание продукта...',
            'price' => '25000',
        ));



        CatalogAttributeGroup::create(array(
            'id' => 1,
            'category_id' => 1,
            'active' => 1,
            'slug' => 'default',
            'lft' => 1,
            'rgt' => 2,
        ));
        CatalogAttributeGroupMeta::create(array(
            'id' => 1,
            'attributes_group_id' => 1,
            'language' => 'ru',
            'active' => 1,
            'name' => 'По умолчанию',
        ));

        CatalogAttribute::create(array(
            'id' => 1,
            'active' => 1,
            'slug' => 'wheel_radius',
            'attributes_group_id' => 1,
            'type' => 'text',
            'lft' => 1,
            'rgt' => 2,
        ));
        CatalogAttributeMeta::create(array(
            'id' => 1,
            'attribute_id' => 1,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Радиус колеса',
        ));

        CatalogAttribute::create(array(
            'id' => 2,
            'active' => 1,
            'slug' => 'material',
            'attributes_group_id' => 1,
            'type' => 'textarea',
            'lft' => 3,
            'rgt' => 4,
        ));
        CatalogAttributeMeta::create(array(
            'id' => 2,
            'attribute_id' => 2,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Материал рамы',
        ));


        CatalogAttributeGroup::create(array(
            'id' => 2,
            'category_id' => 1,
            'active' => 1,
            'slug' => 'additional',
            'lft' => 3,
            'rgt' => 4,
        ));
        CatalogAttributeGroupMeta::create(array(
            'id' => 2,
            'attributes_group_id' => 2,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Дополнительно',
        ));

        CatalogAttribute::create(array(
            'id' => 3,
            'active' => 1,
            'slug' => 'flashlight',
            'attributes_group_id' => 2,
            'type' => 'wysiwyg',
            'lft' => 5,
            'rgt' => 6,
        ));
        CatalogAttributeMeta::create(array(
            'id' => 3,
            'attribute_id' => 3,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Наличие фары освещения',
        ));

        CatalogAttribute::create(array(
            'id' => 4,
            'active' => 1,
            'slug' => 'breaks',
            'attributes_group_id' => 2,
            'type' => 'checkbox',
            'lft' => 7,
            'rgt' => 8,
        ));
        CatalogAttributeMeta::create(array(
            'id' => 4,
            'attribute_id' => 4,
            'language' => 'ru',
            'active' => 1,
            'name' => 'Тормоза',
        ));
    }

}