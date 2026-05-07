<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_topic', function (Blueprint $table) {
            $table->foreignUuid('quiz_uuid')->constrained('quizzes', 'uuid')->onDelete('cascade');
            $table->foreignUuid('topic_uuid')->constrained('topics', 'uuid')->onDelete('cascade');
            $table->primary(['quiz_uuid', 'topic_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_topic');
    }
};
