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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->string('uuid')->primary()->unique();
                $table->string('user_id', 36);
                $table->string('name');
                $table->string('sku');
                $table->string('uom_id', 36)->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('is_stock_tracked')->default(0);
                $table->tinyInteger('is_sellable')->default(0);
                $table->double('sales_price', 15, 2);
                $table->double('purchase_price', 15, 2);
                $table->softDeletes();
                $table->timestamps();

                $table->foreign('user_id')->references('uuid')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('uom_id')->references('uuid')->on('uoms')->onDelete('cascade')->onUpdate('cascade');
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
        if (Schema::hasTable('products')) {
            Schema::dropIfExists('products');
        }
    }
}
