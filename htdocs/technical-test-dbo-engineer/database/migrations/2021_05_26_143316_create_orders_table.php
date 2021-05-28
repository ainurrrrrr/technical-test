<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->string('uuid')->primary()->unique();
                $table->string('user_id', 36);
                $table->string('name')->nullable();
                $table->string('order_number')->unique();
                $table->dateTime('order_date');
                $table->enum('status', ['waiting_confirmation', 'unpaid', 'paid', 'reject', 'complete']);
                $table->double('grand_total', 15, 2);
                $table->double('discount', 15, 2);
                $table->text('shipping_address');
                $table->text('billing_address');
                $table->string('phone_number');
                $table->double('shipping_price', 15, 2);
                $table->softDeletes();
                $table->timestamps();

                $table->foreign('user_id')->references('uuid')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        if (Schema::hasTable('orders')) {
            Schema::dropIfExists('orders');
        }
    }
}
