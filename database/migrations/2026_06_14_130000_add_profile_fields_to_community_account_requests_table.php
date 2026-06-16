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
            $table->text('vision_mission')->nullable()->after('position');
            $table->text('background')->nullable()->after('vision_mission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community_account_requests', function (Blueprint $table) {
            $table->dropColumn(['vision_mission', 'background']);
        });
    }
};
