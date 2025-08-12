<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expert_questions', function (Blueprint $table) {
            $table->boolean('is_approved')->default(true);
            $table->text('moderation_notes')->nullable();
            $table->timestamp('moderated_at')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('expert_questions', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'moderation_notes', 'moderated_at', 'moderated_by']);
        });
    }
};