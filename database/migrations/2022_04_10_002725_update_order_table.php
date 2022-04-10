<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('orders', function (Blueprint $table) {
            $table->double('shipping_charge')->nullable();
            $table->unsignedBigInteger('shipping_charge_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('shipping_charge_id')->references('id')->on('shipping_charges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_charge_id']);
            $table->dropColumn('shipping_charge_id');
            $table->dropColumn('shipping_charge');
        });
    }
}
