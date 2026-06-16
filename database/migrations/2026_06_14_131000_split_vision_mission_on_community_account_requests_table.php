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
        Schema::table('community_account_requests', function (Blueprint $table) {
            $table->text('vision')->nullable()->after('position');
            $table->text('mission')->nullable()->after('vision');
        });

        DB::table('community_account_requests')
            ->whereNotNull('vision_mission')
            ->update([
                'vision' => DB::raw('vision_mission'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community_account_requests', function (Blueprint $table) {
            $table->dropColumn(['vision', 'mission']);
        });
    }
};
