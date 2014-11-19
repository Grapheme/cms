<?php

class CatalogTableSeeder extends Seeder{

	public function run(){

        CatalogCategory::create(array(
            'id' => 1,
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
    }

}