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
        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->foreign('team_id')->references('id')->on('wfum_teams')->onDelete('cascade')->change();
            });
        } else {
            throw new Exception('`roles` table is missing. Make sure Spatie Permissions is installed and migrated');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });
    }
};
