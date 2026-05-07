<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid()->primary()->unique();
            $table->string('name');
            $table->foreignUuid('question_id')->constrained('questions','uuid')->onDelete('cascade');
            $table->boolean('is_correct')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('explanation')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
