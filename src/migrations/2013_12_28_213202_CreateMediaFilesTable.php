<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediaFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('media_files', function(Blueprint $table) {
			$table->increments('id');
			$table->string('path');
			$table->string('filename');
			$table->string('public_url')->nullable();
			$table->string('name')->nullable();
			$table->text('description')->nullable();
			$table->integer('parent_id')->nullable();
			$table->string('parent_type')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('media_files');
	}

}