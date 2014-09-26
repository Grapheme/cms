<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserrequestsTables extends Migration {

    public $table0 = 'user_info';
    public $table1 = 'user_request';
    public $table2 = 'user_request_status';

	public function up(){
        if (!Schema::hasTable($this->table0)) {
    		Schema::create($this->table0, function(Blueprint $table) {
                $table->increments('id');

                $table->string('email', 128)->nullable()->index();
                $table->string('password', 128)->nullable();
                $table->string('name', 128)->nullable();

    			$table->timestamps();
     		});
            echo(' + ' . $this->table0 . PHP_EOL);
        } else {
            echo('...' . $this->table0 . PHP_EOL);
        }

        if (!Schema::hasTable($this->table1)) {
    		Schema::create($this->table1, function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->nullable()->index();

                $table->integer('type')->unsigned()->nullable()->index();
                $table->text('content')->nullable();
                $table->integer('file_id')->unsigned()->nullable();
                $table->integer('status_id')->unsigned()->nullable();

    			$table->timestamps();
     		});
            echo(' + ' . $this->table1 . PHP_EOL);
        } else {
            echo('...' . $this->table1 . PHP_EOL);
        }

        if (!Schema::hasTable($this->table2)) {
    		Schema::create($this->table2, function(Blueprint $table) {
                $table->increments('id');
                $table->integer('request_id')->unsigned()->nullable()->index();
                $table->integer('status_id')->unsigned()->nullable()->index();

    			$table->timestamps();
    		});
            echo(' + ' . $this->table2 . PHP_EOL);
        } else {
            echo('...' . $this->table2 . PHP_EOL);
        }

	}

	public function down(){

		Schema::dropIfExists($this->table0);
        echo(' - ' . $this->table0 . PHP_EOL);

		Schema::dropIfExists($this->table1);
        echo(' - ' . $this->table1 . PHP_EOL);

		Schema::dropIfExists($this->table2);
        echo(' - ' . $this->table2 . PHP_EOL);
	}

}