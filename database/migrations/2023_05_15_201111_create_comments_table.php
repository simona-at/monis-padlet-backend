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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');

            //fk field:
            $table->bigInteger('padlet_id')->unsigned();

            //constraint:
            $table->foreign('padlet_id')
                ->references('id')->on('padlets')
                ->onDelete('cascade');


            //fk field:
            $table->bigInteger('user_id')->unsigned();//->default('1');

            //constraint:
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
