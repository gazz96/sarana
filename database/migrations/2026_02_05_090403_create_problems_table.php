<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_technician_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_management_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_finance_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('issue');
            $table->text('note')->nullable();
            $table->enum('status', [
                '0', // DRAFT
                '1', // DIAJUKAN
                '2', // PROSES
                '3', // SELESAI DIKERJAKAN
                '4', // DIBATALKAN
                '5', // MENUNGGU PERSETUJUAN MANAGEMENT
                '6', // MENUNGGU PERSETUJUAN ADMIN
                '7', // MENUNGGU PERSETUJUAN KEUANGAN
            ])->default('0');
            $table->dateTime('date')->default(now());
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('code');
            $table->index('status');
            $table->index('user_id');
            $table->index('user_technician_id');
            $table->index('user_management_id');
            $table->index('user_finance_id');
            $table->index('admin_id');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problems');
    }
}
