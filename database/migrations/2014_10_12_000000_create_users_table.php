<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->commit('資料表 ID');
            $table->bigInteger('fbid')->default(0)->commit('Facebook APP ID');
            $table->integer('created_user')->default(0)->comment('建立 ID');
            $table->integer('updated_user')->default(0)->comment('修改 ID');
            $table->integer('deleted_user')->default(0)->comment('刪除 ID');
            $table->string('name')->commit('使用者名稱');
            $table->string('email')->unique()->commit('使用者信箱');
            $table->string('password')->commit('使用者密碼');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
