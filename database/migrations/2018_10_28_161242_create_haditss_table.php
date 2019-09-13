<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHaditssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('haditss', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nomor');
            $table->string('kitab');
            $table->string('bab');
            $table->text('isi');
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
        Schema::dropIfExists('haditss');
    }
}
