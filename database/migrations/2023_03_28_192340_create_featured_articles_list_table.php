<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('featured_articles_list', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Article::class);
            $table->foreignIdFor(\App\Models\ArticleList::class);
            $table->integer('order')->nullable('true');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_articles_list');
    }
};
