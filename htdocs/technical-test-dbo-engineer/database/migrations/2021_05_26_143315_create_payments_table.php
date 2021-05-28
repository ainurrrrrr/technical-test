<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->string('uuid')->primary()->unique();
                $table->string('user_id', 36);
                $table->string('payment_method_id', 36)->nullable();
                $table->string('order_number')->unique();
                $table->double('amount_due', 15, 2);
                $table->dateTime('order_date');
                $table->string('reference_number')->nullable();
                $table->dateTime('payment_date')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->foreign('user_id')->references('uuid')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('payment_method_id')->references('uuid')->on('payment_methods')->onDelete('cascade')->onUpdate('cascade');
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
        if (Schema::hasTable('payments')) {
            Schema::dropIfExists('payments');
        }
    }
}
