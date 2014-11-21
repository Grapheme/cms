<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCatalogTables extends Migration {

    private $prefix = 'catalog_';

	public function up(){

        $this->table = $this->prefix . "categories";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {

                $table->increments('id');
                $table->smallInteger('active')->unsigned()->default(1)->index();
                $table->string('slug')->nullable()->unique();

                $table->text('settings')->nullable();
                $table->integer('lft')->unsigned()->nullable()->index();
                $table->integer('rgt')->unsigned()->nullable()->index();
                $table->timestamps();
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

        $this->table = $this->prefix . "categories_meta";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {

                $table->increments('id');
                $table->integer('category_id')->unsigned()->index();
                $table->string('language')->nullable()->index();
                $table->smallInteger('active')->unsigned()->default(1)->index();
                $table->string('name')->nullable();

                $table->text('settings')->nullable();
                $table->timestamps();

                $table->unique(array('category_id', 'language'), 'category_language');
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }



        $this->table = $this->prefix . "products";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {

                $table->increments('id');
                $table->smallInteger('active')->unsigned()->default(0)->index();
                $table->string('slug')->nullable()->unique();
                $table->integer('category_id')->unsigned()->nullable()->index();

                $table->text('settings')->nullable();
                $table->integer('lft')->unsigned()->nullable()->index();
                $table->integer('rgt')->unsigned()->nullable()->index();
                $table->timestamps();
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

        $this->table = $this->prefix . "products_meta";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {

                $table->increments('id');
                $table->integer('product_id')->unsigned()->nullable()->index();
                $table->string('language')->nullable()->index();
                $table->smallInteger('active')->unsigned()->default(0)->index();
                $table->string('name')->nullable()->index();

                $table->text('settings')->nullable();
                $table->timestamps();

                $table->unique(array('product_id', 'language'), 'product_id_language');
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }



        $this->table = $this->prefix . "attributes_groups";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {

                $table->increments('id');
                $table->integer('category_id')->unsigned()->nullable()->index();
                $table->smallInteger('active')->unsigned()->default(0)->index();
                $table->string('slug')->nullable()->unique();

                $table->text('settings')->nullable();
                $table->integer('lft')->unsigned()->nullable()->index();
                $table->integer('rgt')->unsigned()->nullable()->index();
                $table->timestamps();
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

        $this->table = $this->prefix . "attributes_groups_meta";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {

                $table->increments('id');
                $table->integer('attributes_group_id')->unsigned()->nullable()->index();
                $table->string('language')->nullable()->index();
                $table->smallInteger('active')->unsigned()->default(0)->index();
                $table->string('name')->nullable()->index();

                $table->text('settings')->nullable();
                $table->timestamps();

                $table->unique(array('attributes_group_id', 'language'), 'attributes_group_id_language');
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

        $this->table = $this->prefix . "attributes";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {

                $table->increments('id');
                $table->smallInteger('active')->unsigned()->default(0)->index();
                $table->string('slug')->nullable()->unique();
                $table->integer('attributes_group_id')->unsigned()->nullable()->index();

                $table->text('settings')->nullable();
                $table->integer('lft')->unsigned()->nullable()->index();
                $table->integer('rgt')->unsigned()->nullable()->index();
                $table->timestamps();
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

        $this->table = $this->prefix . "attributes_meta";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {

                $table->increments('id');
                $table->integer('attribute_id')->unsigned()->nullable()->index();
                $table->string('language')->nullable()->index();
                $table->smallInteger('active')->unsigned()->default(0)->index();
                $table->string('name')->nullable()->index();

                $table->text('settings')->nullable();
                $table->timestamps();

                $table->unique(array('attribute_id', 'language'), 'attribute_id_language');
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }

    }


	public function down(){

        Schema::dropIfExists($this->prefix . "categories");
        echo(' - ' . $this->prefix . "categories" . PHP_EOL);

        Schema::dropIfExists($this->prefix . "categories_meta");
        echo(' - ' . $this->prefix . "categories_meta" . PHP_EOL);



        Schema::dropIfExists($this->prefix . "products");
        echo(' - ' . $this->prefix . "products" . PHP_EOL);

        Schema::dropIfExists($this->prefix . "products_meta");
        echo(' - ' . $this->prefix . "products_meta" . PHP_EOL);



        Schema::dropIfExists($this->prefix . "attributes_groups");
        echo(' - ' . $this->prefix . "attributes_groups" . PHP_EOL);

        Schema::dropIfExists($this->prefix . "attributes_groups_meta");
        echo(' - ' . $this->prefix . "attributes_groups_meta" . PHP_EOL);

        Schema::dropIfExists($this->prefix . "attributes");
        echo(' - ' . $this->prefix . "attributes" . PHP_EOL);

        Schema::dropIfExists($this->prefix . "attributes_meta");
        echo(' - ' . $this->prefix . "attributes_meta" . PHP_EOL);
	}

}

