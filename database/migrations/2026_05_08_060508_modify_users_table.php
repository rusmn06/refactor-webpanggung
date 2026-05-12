<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom email karena pakai username
            $table->dropColumn('email');

            // Tambah kolom baru
            $table->string('username')->unique()->after('name');
            $table->enum('role', ['admin', 'user'])->default('user')->after('username');
            $table->string('avatar')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->after('name');
            $table->dropColumn(['username', 'role', 'avatar']);
        });
    }
};
