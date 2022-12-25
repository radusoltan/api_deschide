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
        Schema::create('image_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')
                ->references('id')
                ->on('images')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title')->index();
            $table->string('author')->nullable(true);
            $table->text('description')->nullable(true);
//            $table->text('body')->nullable(true);
            $table->unique(['locale','title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_translations');
    }
};
