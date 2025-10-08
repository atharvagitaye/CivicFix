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
        Schema::table('issues', function (Blueprint $table) {
            // Add new columns that our application expects
            $table->foreignId('user_id')->after('description')->constrained()->onDelete('cascade');
            $table->string('status')->after('sub_category_id')->default('submitted');
            $table->string('location')->after('priority')->nullable();
            $table->decimal('latitude', 10, 8)->after('location')->nullable();
            $table->decimal('longitude', 11, 8)->after('latitude')->nullable();
            $table->foreignId('subcategory_id')->after('category_id')->nullable()->constrained('sub_categories')->onDelete('set null');
            
            // Modify existing columns
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->change();
            
            // Drop columns that we don't need
            $table->dropForeign(['reported_by']);
            $table->dropColumn('reported_by');
            $table->dropForeign(['assigned_to']);
            $table->dropColumn('assigned_to');
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
            $table->dropColumn(['location_lat', 'location_lng']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issues', function (Blueprint $table) {
            // Reverse the changes
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('status');
            $table->dropColumn(['location', 'latitude', 'longitude']);
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn('subcategory_id');
            
            // Add back the old columns
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('staff')->onDelete('set null');
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->decimal('location_lat', 10, 8);
            $table->decimal('location_lng', 11, 8);
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium')->change();
        });
    }
};
