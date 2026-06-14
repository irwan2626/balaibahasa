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
        Schema::table('community_account_requests', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->timestamp('terms_accepted_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community_account_requests', function (Blueprint $table) {
            $table->dropColumn(['password', 'terms_accepted_at']);
        });
    }
};
