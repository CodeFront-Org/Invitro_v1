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
            $table->smallInteger('quantity');//number of items
            $table->string('batch_no')->unique();
            $table->tinyInteger('sold');//To know how many batches have been sold soo far
            $table->tinyInteger('sold_out')->default(0);//to know if item is sold out already for easier query
            $table->string('quantity_type');// type of e.g cartons, packets etc
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