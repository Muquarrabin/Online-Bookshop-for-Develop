<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecondHandAccTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('second_hand_acc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('selling_request_id')->unsigned()->index();
            $table->integer('order_id')->unsigned()->index();
            $table->double('asking_price');
            $table->double('selling_price');
            $table->double('discount')->default(0);
            $table->double('commission_earned');
            $table->boolean('payment_status')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('second_hand_acc', function (Blueprint $table){
            $table->foreign('selling_request_id')->references('id')->on('book_selling_requests')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('second_hand_acc');
    }
}
