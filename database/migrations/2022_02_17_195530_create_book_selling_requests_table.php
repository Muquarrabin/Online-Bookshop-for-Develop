<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookSellingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_selling_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_id')->unique();
            $table->string('book_title');
            $table->string('book_slug')->unique();
            $table->text('book_description');
            $table->integer('author_id')->unsigned()->index();
            $table->integer('category_id')->unsigned()->index();
            $table->integer('image_id')->unsigned()->index();
            $table->double('commission');
            $table->string('seller_name');
            $table->string('seller_mobile');
            $table->string('seller_email');
            $table->text('seller_address');
            $table->double('selling_price');
            $table->double('asking_price');
            $table->boolean('status')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('book_selling_requests', function (Blueprint $table){
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_selling_requests');
    }
}
