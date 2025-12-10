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
        Schema::create('users_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')  
                ->on('users')
                ->onDelete('cascade');

            $table->string('full_name', 255)->nullable();

            $table->string('phone_number', 20)->unique();

            $table->text('address');

            $table->enum('gender', ['male', 'female']);

            $table->string('profile_picture', 255)->nullable();

            $table->string('member_type')->default('public');

            $table->string('institution_name', 100)->nullable();

            $table->string('identity_number', 100)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
