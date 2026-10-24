<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 80);
            $table->string('model', 100);
            $table->unsignedSmallInteger('year');
            $table->decimal('rent_price', 12, 2);
            $table->string('image');
            $table->string('transmission', 20);
        });
    }
    public function down(): void { Schema::dropIfExists('cars'); }
};
