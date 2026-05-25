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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->morphs('visitable');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('visitor_type', 20);
            $table->string('visitor_hash', 64);
            $table->string('interval', 20);
            $table->string('interval_key', 32);
            $table->json('data')->nullable();
            $table->timestamp('visited_at')->index();
            $table->timestamps();

            $table->unique([
                'visitable_type',
                'visitable_id',
                'visitor_type',
                'visitor_hash',
                'interval',
                'interval_key',
            ], 'visits_unique_interval');

            $table->index(['visitable_type', 'visitable_id', 'visited_at'], 'visits_visitable_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
