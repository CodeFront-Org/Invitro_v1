<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('user_id');
            $table->smallInteger('product_id');
            $table->smallInteger('quantity');
            $table->integer('amount')->nullable();
            $table->tinyInteger('type')->default(0);// 0 for returned stocks and 1 for new stocks
            $table->string('source');
            $table->tinyInteger('approve')->default(0);
            $table->string('remarks');
            $table->dateTime('expiry_date');
            $table->softDeletes();
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
        Schema::dropIfExists('stocks');
    }
}