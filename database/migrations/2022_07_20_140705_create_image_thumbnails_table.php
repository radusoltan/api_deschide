<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Rendition;
use App\Models\Image;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_thumbnails', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Image::class);
            $table->foreignIdFor(Rendition::class);
            $table->integer('width')->nullable(true);
            $table->integer('height')->nullable(true);
            $table->string('path');
            $table->json('coords')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_thumbnails');
    }
};
