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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->enum('status', ['created', 'under_process', 'resolved'])->default('created');
            $table->string('latitude', 50);
            $table->string('longitude', 50);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
