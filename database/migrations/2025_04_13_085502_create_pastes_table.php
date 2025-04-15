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
        Schema::create('pastes', function (Blueprint $table) {
            $table->id();
            $table->string('paste_name');
            $table->string('paste_format');
            $table->string('paste_private');
            $table->string('paste_expire_date');
            $table->longText('paste_code');
            $table->string('url');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('info_pastes', function (Blueprint $table) {
            $table->id();
            $table->string('paste_url');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pastes');
    }
};
