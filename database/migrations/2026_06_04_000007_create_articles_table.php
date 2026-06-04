<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('restrict');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->json('content_json');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->enum('status', ['DRAFT', 'UNDER_REVIEW', 'PUBLISHED'])->default('DRAFT');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at'], 'idx_articles_status_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
