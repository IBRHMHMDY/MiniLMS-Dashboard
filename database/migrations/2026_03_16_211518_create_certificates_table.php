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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            
            // رقم فريد مرجعي للتحقق من صحة الشهادة عبر الـ QR Code أو الرابط
            $table->string('certificate_number')->unique(); 
            
            // مسار ملف الـ PDF (اختياري، في حال تم توليدها وحفظها)
            $table->string('file_path')->nullable(); 
            
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamps();
            
            // منع إصدار أكثر من شهادة لنفس الطالب في نفس الكورس
            $table->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
