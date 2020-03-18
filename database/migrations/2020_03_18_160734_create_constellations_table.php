<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constellations', function (Blueprint $table) {
            $table->tinyIncrements('id')->commit('資料表 ID');
            $table->integer('created_user')->default(0)->comment('建立 ID');
            $table->integer('updated_user')->default(0)->comment('修改 ID');
            $table->integer('deleted_user')->default(0)->comment('刪除 ID');
            $table->string('name', 16)->commit('名稱');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('constellations');
    }
}
