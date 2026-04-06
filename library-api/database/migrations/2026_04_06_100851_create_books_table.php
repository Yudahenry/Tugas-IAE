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
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('title', 200);
        $table->string('author', 150);
        $table->string('isbn', 20)->unique();
        $table->string('category', 100)->nullable();
        $table->string('publisher', 150)->nullable();
        $table->year('year')->nullable();
        $table->integer('stock')->default(0);
        $table->text('description')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
