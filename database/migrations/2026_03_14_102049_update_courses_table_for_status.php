<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('is_published');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->after('category_id');
            $table->text('rejection_reason')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('category_id');
            $table->dropColumn(['status', 'rejection_reason']);
        });
    }
};