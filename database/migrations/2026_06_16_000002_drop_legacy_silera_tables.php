<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('collaboration_requests');
        Schema::dropIfExists('activity_reports');
        Schema::dropIfExists('literacy_programs');
        Schema::dropIfExists('literacy_posts');
        Schema::dropIfExists('community_members');
        Schema::dropIfExists('community_category');
        Schema::dropIfExists('literacy_communities');
        Schema::dropIfExists('literacy_categories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tabel legacy tidak dibuat ulang karena sistem sekarang memakai tabel aktif:
        // community_account_requests dan community_stories.
    }
};
