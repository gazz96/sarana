<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('guard_name')->default('web');
            $table->timestamps();
            
            // Index for faster lookups
            $table->index('name');
            $table->index('guard_name');
        });
        
        // Insert default roles
        DB::table('roles')->insert([
            ['name' => 'guru', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'teknisi', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'lembaga', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'keuangan', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
