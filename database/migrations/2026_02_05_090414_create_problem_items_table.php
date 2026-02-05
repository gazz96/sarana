<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('problem_id')->constrained('problems')->onDelete('cascade');
            $table->foreignId('good_id')->constrained('goods')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->text('photos')->nullable(); // JSON field for photos
            $table->timestamps();
            
            $table->index('problem_id');
            $table->index('good_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problem_items');
    }
}
