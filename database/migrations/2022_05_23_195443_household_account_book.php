<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HouseholdAccountBook extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('payment_name',100);
            $table->date('date');
            $table->integer('user_id');//
            $table->integer('spending')->nullable();//支出
            $table->integer('income')->nullable();//収入
            $table->timestamps();//データ更新日
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}