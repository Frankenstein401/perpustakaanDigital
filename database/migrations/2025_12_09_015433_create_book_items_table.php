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
        Schema::create('book_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->uuid('book_id');
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');

            $table->uuid('shelf_id');
            $table->foreign('shelf_id')
                ->references('id')
                ->on('shelves')
                ->onDelete('cascade');

            $table->string('inventory_code', 100);

            $table->enum('condition', ['good', 'damaged', 'lost'])
                ->default('good');

            $table->enum('status', ['available', 'borrowed', 'maintenance', 'lost'])
                ->default('available');

            $table->date('procured_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_items');
    }
};
