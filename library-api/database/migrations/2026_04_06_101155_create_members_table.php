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
    Schema::create('members', function (Blueprint $table) {
        $table->id();
        $table->string('name', 150);
        $table->string('member_code', 20)->unique();
        $table->string('email', 150)->unique();
        $table->string('phone', 20)->nullable();
        $table->text('address')->nullable();
        $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
        $table->date('joined_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
