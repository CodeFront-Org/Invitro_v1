<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('user_id');
            $table->string('name')->unique();
            $table->integer('quantity')->default(0);
            $table->integer('expire_days');
            $table->smallInteger('order_level');
            $table->string('quantity_type')->nullable();
            $table->tinyInteger('approve')->default(0);// Determine approval of new products
            $table->tinyInteger('is_order_level')->default(0);// Determine if order will be excecuted
            $table->integer('staff_order_level')->nullable();//Staff who requested order to get order limit
            $table->integer('ordered_qty')->nullable();//This is the quantity that was ordered before informing admin that order level has been reached
            $table->integer('allowed_qty')->nullable();//This will be inserted by admin
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
        Schema::dropIfExists('products');
    }
}
