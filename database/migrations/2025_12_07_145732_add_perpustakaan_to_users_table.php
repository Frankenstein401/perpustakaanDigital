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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 100)->unique()->after('id');

            $table->enum('verification_status', ['unverified', 'pending', 'verified'])
                ->default('unverified')
                ->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'id',
                'username',
                'email',
                'email_verified_at',
                'password',
                'remember_token',
                'verification_status',
                'created_at',
                'update_at' 
            ]);      
        });
    }
};
