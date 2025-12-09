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
        Schema::create('loans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('loan_code');

            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')  
                ->on('users')
                ->onDelete('cascade');

            $table->uuid('book_item_id');
            $table->foreign('book_item_id')
                ->references('id')
                ->on('book_items')
                ->onDelete('cascade');

            $table->timestamp('borrow_date')->useCurrent(); // Otomatis jam sekarang pas dibuat

            $table->timestamp('due_date')->nullable(); // Batas waktu (bisa diset jam 23:59 hari H)

            $table->timestamp('return_date')->nullable(); // Jam dikembalikan

            $table->enum('status', ['active', 'returned', 'overdue']);

            $table->uuid('created_by');
            $table->foreign('created_by')
                ->references('id')  
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
