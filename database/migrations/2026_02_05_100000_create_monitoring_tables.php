<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // User activities table
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action')->index();
            $table->json('details')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('created_at');
        });

        // System errors table
        Schema::create('system_errors', function (Blueprint $table) {
            $table->id();
            $table->string('exception_type')->index();
            $table->text('message');
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->text('stack_trace')->nullable();
            $table->json('context')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->index('created_at');
            $table->index('exception_type');
        });

        // Performance logs table
        Schema::create('performance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('route')->index();
            $table->decimal('response_time_ms', 10, 2)->index();
            $table->decimal('memory_usage_mb', 10, 2)->nullable();
            $table->integer('status_code')->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_logs');
        Schema::dropIfExists('system_errors');
        Schema::dropIfExists('user_activities');
    }
}