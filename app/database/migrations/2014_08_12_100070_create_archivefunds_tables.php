<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArchivefundsTables extends Migration {

    public $table0 = 'archive_funds';

	public function up(){
        if (!Schema::hasTable($this->table0)) {
    		Schema::create($this->table0, function(Blueprint $table) {
                $table->increments('id');

                $table->integer('fund_number')->unsigned()->nullable()->index();
                $table->string('name', 128)->nullable();

                $table->date('date_start')->nullable()->index();
                $table->date('date_stop')->nullable()->index();

                $table->integer('type_id')->unsigned()->nullable()->index();

                $table->timestamps();
     		});
            echo(' + ' . $this->table0 . PHP_EOL);
        } else {
            echo('...' . $this->table0 . PHP_EOL);
        }

	}

	public function down(){

		Schema::dropIfExists($this->table0);
        echo(' - ' . $this->table0 . PHP_EOL);

	}

}