<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('agama')->nullable();
            $table->date('tanggal_lahir')->nullable()->after('agama');
            $table->string('tempat_lahir')->nullable()->after('tanggal_lahir');
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['agama', 'tanggal_lahir', 'tempat_lahir']);
        });
    }
};
