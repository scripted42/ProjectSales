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
        Schema::table('videos', function (Blueprint $table) {
            $table->string('video_path')->nullable()->after('url');
            $table->string('type')->default('youtube')->after('video_path'); // 'youtube' or 'popup'
            $table->string('url')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'type']);
            $table->string('url')->nullable(false)->change();
        });
    }
};
