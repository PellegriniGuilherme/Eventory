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
        Schema::table('close_sales', function (Blueprint $table) {
            $table->decimal('total_profit', 8, 2)->after('total_tax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('close_sales', function (Blueprint $table) {
            $table->dropColumn('total_profit');
        });
    }
};
