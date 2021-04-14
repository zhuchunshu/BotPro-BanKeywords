<?php

namespace App\Plugins\Bankeywords\src\database;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanKeywordsCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('bankeywords_ci')) {
            Schema::create('bankeywords_ci', function (Blueprint $table) {
                $table->increments('id');
                $table->string('content')->comment('敏感词');
                $table->string('type')->comment('触发条件');
                $table->string('event')->comment('触发事件')->default('撤回');
                $table->string('events')->comment('第二事件')->nullable();
                $table->integer('ban_time')->comment('禁言时长')->nullable();
                $table->integer('ban_time2')->comment('撤回触发的禁言时长')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bankeywords_ci');
    }
}
