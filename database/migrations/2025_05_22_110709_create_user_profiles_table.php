<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Data pribadi
            $table->string('nik')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('status')->nullable(); // status pernikahan misalnya
            $table->text('address')->nullable();
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->integer('height')->nullable(); // dalam cm
            $table->integer('weight')->nullable(); // dalam kg
            $table->string('education')->nullable();
            $table->string('bank_account')->nullable();

            // Data pekerjaan
            $table->string('employee_status')->nullable(); // aktif / tidak
            $table->string('position')->nullable();
            $table->string('work_duration')->nullable(); // e.g., "5 Tahun"
            $table->string('placement_location')->nullable();

            // Portofolio dan lainnya
            $table->string('portfolio_link')->nullable();
            $table->text('work_experience')->nullable(); // bisa disimpan dalam JSON juga kalau banyak
            $table->text('skills')->nullable(); // misalnya dalam JSON: [{"kategori": "...", "deskripsi": "..."}]

            $table->string('grade')->nullable(); // Grade: A, B, dll
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
}
