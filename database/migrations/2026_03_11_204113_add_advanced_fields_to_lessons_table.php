<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->json('attachments')->nullable()->after('video_url');
            $table->integer('duration_in_minutes')->nullable()->after('attachments');
            $table->boolean('is_free_preview')->default(false)->after('duration_in_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['attachments', 'duration_in_minutes', 'is_free_preview']);
        });
    }
};