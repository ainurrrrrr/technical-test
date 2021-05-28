<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('uoms')) {
            Schema::create('uoms', function (Blueprint $table) {
                $table->string('uuid')->primary()->unique();
                $table->string('name', 45);
                $table->string('code', 45);
                $table->softDeletes();
                $table->timestamps();
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
        if (Schema::hasTable('uoms')) {
            Schema::dropIfExists('uoms');
        }
    }
}
