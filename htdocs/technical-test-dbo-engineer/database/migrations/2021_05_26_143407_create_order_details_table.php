<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('order_details')) {
            Schema::create('order_details', function (Blueprint $table) {
                $table->string('uuid')->primary()->unique();
                $table->string('user_id', 36);
                $table->string('order_id', 36);
                $table->string('product_id', 36);
                $table->string('product_name');
                $table->double('quantity', 15, 2);
                $table->double('price', 15, 2);
                $table->double('discount', 15, 2);
                $table->text('description');
                $table->double('weight', 15, 2);
                $table->softDeletes();
                $table->timestamps();

                $table->foreign('user_id')->references('uuid')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('order_id')->references('uuid')->on('orders')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('product_id')->references('uuid')->on('products')->onDelete('cascade')->onUpdate('cascade');
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
        if (Schema::hasTable('order_details')) {
            Schema::dropIfExists('order_details');
        }
    }
}
