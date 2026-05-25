<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timeline_events', function (Blueprint $table) {
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('timeline_events', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
            $table->dropColumn(['batch_id', 'event_date', 'location', 'image', 'is_published']);
        });
    }
};
