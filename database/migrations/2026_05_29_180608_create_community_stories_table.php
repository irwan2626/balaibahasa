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
        Schema::create('community_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_account_request_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name');
            $table->string('author_email');
            $table->string('title');
            $table->text('story');
            $table->string('photo_path')->nullable();
            $table->enum('status', ['draft', 'submitted', 'published', 'rejected'])->default('submitted');
            $table->timestamps();

            $table->index(['author_email', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_stories');
    }
};
