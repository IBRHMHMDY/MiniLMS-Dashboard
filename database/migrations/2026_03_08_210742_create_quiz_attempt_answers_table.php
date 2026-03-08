<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            // الربط مع المحاولة الأساسية
            $table->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
            // الربط مع السؤال
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            // الربط مع الإجابة التي اختارها المستخدم
            $table->foreignId('selected_answer_id')->constrained('answers')->cascadeOnDelete();
            // هل إجابته كانت صحيحة؟
            $table->boolean('is_correct');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_answers');
    }
};
