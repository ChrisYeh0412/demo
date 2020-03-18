<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstellationsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constellations_details', function (Blueprint $table) {
            $table->bigIncrements('id')->commit('資料表 ID');
            $table->tinyInteger('constellation_id')->default(0)->commit('關聯 constellation 資料表');
            $table->tinyInteger('type')->default(0)->commit('0.整體運勢 1.愛情運勢 2.事業運勢 3.財運運勢');
            $table->integer('created_user')->default(0)->comment('建立 ID');
            $table->integer('updated_user')->default(0)->comment('修改 ID');
            $table->integer('deleted_user')->default(0)->comment('刪除 ID');
            $table->string('name', 16)->commit('名稱');
            $table->text('contents')->commit('內容');
            $table->date('date')->commit('日期');
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
        Schema::dropIfExists('constellations_details');
    }
}
