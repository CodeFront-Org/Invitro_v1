<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity'); // Assuming we need to know how much was restocked
            $table->string('source')->nullable(); // e.g. import / local
            $table->decimal('landing_cost', 15, 2)->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('delivery_note')->nullable();
            $table->text('remarks')->nullable();
            $table->date('restock_date')->nullable();
            $table->timestamps();

            // Foreign key constraint (optional but good practice)
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restocks');
    }
}
