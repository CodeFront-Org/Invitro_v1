<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no')->unique();
            $table->smallInteger('quantity');
            $table->integer('cost')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('sold')->default(0);//to show quantity that has been sold. so as to track and avoid over selling of a batch.
            $table->tinyInteger('sold_out')->default(0); //when whole batch is sold out then status changes to 1 and not list it for sell.
            $table->tinyInteger('approved')->default(0); //when whole batch is sold out then status changes to 1 and not list it for sell.
            $table->datetime('expiry_date');
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
        Schema::dropIfExists('batches');
    }
}
