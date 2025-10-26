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
        Schema::table('issue_assignments', function (Blueprint $table) {
            // Add unique constraint on issue_id to ensure one assignment per issue
            $table->unique('issue_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issue_assignments', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique(['issue_id']);
        });
    }
};
