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
        Schema::create('literacy_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 20)->default('#00236f');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('literacy_communities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('Komunitas');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('village')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->default('Riau');
            $table->string('postal_code', 12)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('social_media')->nullable();
            $table->date('founded_on')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('cover_path')->nullable();
            $table->enum('status', ['draft', 'pending', 'verified', 'inactive'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['city', 'district']);
            $table->index('status');
        });

        Schema::create('community_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('literacy_community_id')->constrained()->cascadeOnDelete();
            $table->foreignId('literacy_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['literacy_community_id', 'literacy_category_id'], 'community_category_unique');
        });

        Schema::create('community_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('literacy_community_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->enum('role', ['founder', 'coordinator', 'member', 'volunteer'])->default('member');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('joined_at')->nullable();
            $table->timestamps();

            $table->index(['literacy_community_id', 'role']);
        });

        Schema::create('literacy_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('literacy_community_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['news', 'article', 'agenda'])->default('news');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('thumbnail_path')->nullable();
            $table->enum('status', ['draft', 'review', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
            $table->index('published_at');
        });

        Schema::create('literacy_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('literacy_community_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('target_participants')->default(0);
            $table->unsignedInteger('actual_participants')->default(0);
            $table->enum('status', ['planned', 'active', 'completed', 'cancelled'])->default('planned');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'start_date']);
        });

        Schema::create('activity_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('literacy_community_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->date('activity_date');
            $table->unsignedInteger('participants_count')->default(0);
            $table->text('summary')->nullable();
            $table->text('outcome')->nullable();
            $table->string('attachment_path')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['activity_date', 'status']);
        });

        Schema::create('collaboration_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_community_id')->constrained('literacy_communities')->cascadeOnDelete();
            $table->foreignId('partner_community_id')->nullable()->constrained('literacy_communities')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('proposed_date')->nullable();
            $table->enum('status', ['open', 'accepted', 'completed', 'cancelled'])->default('open');
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
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
};
