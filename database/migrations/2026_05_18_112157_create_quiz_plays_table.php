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
        Schema::create('quiz_plays', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('front_user_id')->constrained('frontend_users','uuid')->onDelete('cascade');
            $table->foreignUuid('quiz_id')->constrained('quizzes','uuid')->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->integer('progress')->default(0);
            $table->json('answers')->nullable();
            $table->string('mode')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_plays');
    }
};
