<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('color', 7)->default('#E57399');
            $table->boolean('is_private')->default(false);
            $table->boolean('requires_approval')->default(false);
            $table->foreignId('creator_id')->constrained('users');
            $table->integer('member_count')->default(0);
            $table->timestamps();
        });

        // Grup üyelikleri tablosu
        Schema::create('forum_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('forum_groups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['member', 'moderator', 'admin'])->default('member');
            $table->boolean('is_approved')->default(true);
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['group_id', 'user_id']);
        });

        // Forum konularına grup bağlantısı
        Schema::table('forum_topics', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->constrained('forum_groups')->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('forum_topics', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
        });
        
        Schema::dropIfExists('forum_group_members');
        Schema::dropIfExists('forum_groups');
    }
};