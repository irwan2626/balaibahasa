<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('community_story_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_story_id')->constrained()->cascadeOnDelete();
            $table->string('photo_path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['community_story_id', 'sort_order']);
        });

        if (Schema::hasColumn('community_stories', 'photo_path')) {
            DB::table('community_stories')
                ->whereNotNull('photo_path')
                ->orderBy('id')
                ->get(['id', 'photo_path', 'created_at', 'updated_at'])
                ->each(function ($story) {
                    DB::table('community_story_photos')->insert([
                        'community_story_id' => $story->id,
                        'photo_path' => $story->photo_path,
                        'sort_order' => 0,
                        'created_at' => $story->created_at ?? now(),
                        'updated_at' => $story->updated_at ?? now(),
                    ]);
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_story_photos');
    }
};
