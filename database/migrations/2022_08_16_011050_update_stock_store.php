<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_generals', function (Blueprint $table) {
            $table->foreignId('store_id')->after('user_id')->constrained('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_generals', function (Blueprint $table) {
            $table->dropForeign('stock_generals_store_id_foreign');
        });
    }
};
