<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('payment_methods')) {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->string('uuid')->primary()->unique();
                $table->string('user_id', 36);
                $table->string('name');
                $table->tinyInteger('is_active')->default(1);
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
        if (Schema::hasTable('payment_methods')) {
            Schema::dropIfExists('payment_methods');
        }
    }
}
