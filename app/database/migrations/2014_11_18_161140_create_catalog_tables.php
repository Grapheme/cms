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

                $table->integer('category_id')->unsigned()->index();
                $table->string('language')->nullable()->index();
                $table->smallInteger('active')->unsigned()->default(1)->index();
                $table->string('name')->nullable();

                $table->text('settings')->nullable();
                $table->timestamps();

                $table->unique(array('category_id', 'language'));
            });
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }


        $this->table = $this->prefix . "goods";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {

                $table->increments('id');
                $table->smallInteger('active')->unsigned()->default(0)->index();
                $table->string('slug')->nullable()->unique();
                $table->string('name')->nullable()->index();
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


        $this->table = $this->prefix . "attributes";
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function(Blueprint $table) {

                $table->increments('id');
                $table->smallInteger('active')->unsigned()->default(0)->index();
                $table->string('slug')->nullable()->unique();
                $table->string('name')->nullable()->index();
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

    }


	public function down(){

        Schema::dropIfExists($this->prefix . "categories");
        echo(' - ' . $this->prefix . "categories" . PHP_EOL);

        Schema::dropIfExists($this->prefix . "categories_meta");
        echo(' - ' . $this->prefix . "categories_meta" . PHP_EOL);

        Schema::dropIfExists($this->prefix . "goods");
        echo(' - ' . $this->prefix . "goods" . PHP_EOL);

        Schema::dropIfExists($this->prefix . "attributes");
        echo(' - ' . $this->prefix . "attributes" . PHP_EOL);
	}

}
