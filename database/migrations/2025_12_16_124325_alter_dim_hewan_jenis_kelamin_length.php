<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dim_hewan', function (Blueprint $table) {
            $table->string('jenis_kelamin', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('dim_hewan', function (Blueprint $table) {
            $table->string('jenis_kelamin', 10)->nullable()->change();
        });
    }
};
