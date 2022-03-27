<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->boolean('is_second_hand')->default(false);
            $table->integer('selling_request_id')->unsigned()->index()->nullable();
        });
        Schema::table('books', function (Blueprint $table){
            $table->foreign('selling_request_id')->references('id')->on('book_selling_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['is_second_hand']);
            $table->dropForeign(['selling_request_id']);
            $table->dropColumn(['selling_request_id']);
        });
    }
}
