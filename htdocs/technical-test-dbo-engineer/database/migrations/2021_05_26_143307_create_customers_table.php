<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->string('uuid')->primary()->unique();
                $table->string('email')->unique();
                $table->string('user_id', 36);
                $table->string('name');
                $table->text('address');
                $table->string('phone_number');
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
        if (Schema::hasTable('customers')) {
            Schema::dropIfExists('customers');
        }
    }
}
