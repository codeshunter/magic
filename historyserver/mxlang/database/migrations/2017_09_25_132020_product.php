<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('enname');
            $table->string('zhname');
            $table->string('runame');
            $table->text('endet');
            $table->text('zhdet');
            $table->text('rudet');
            $table->string('enprice');
            $table->string('zhprice');
            $table->string('ruprice');
            $table->integer('sid');
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
        //
    }
}
