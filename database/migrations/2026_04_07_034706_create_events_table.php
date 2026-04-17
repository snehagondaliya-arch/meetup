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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug'); 
            $table->string('date_list_view')->nullable();
            $table->string('datetime_text')->nullable();
            $table->string('venue_name')->nullable();
            $table->text('full_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('image_url')->nullable();
            $table->string('group_image')->nullable();
            $table->text('description')->nullable();
            $table->string('host_name')->nullable();
            $table->string('host_image')->nullable();
            $table->string('attendees')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('is_online')->default(false);
            $table->text('event_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
