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
        Schema::create('conversation_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('selected_language_id')->nullable()->constrained('languages')->nullOnDelete();
            $table->foreignId('matched_language_id')->nullable()->constrained('languages')->nullOnDelete();
            $table->text('user_message');
            $table->text('bot_message');
            $table->boolean('is_valid');
            $table->string('error_type')->nullable();
            $table->text('suggestion')->nullable();
            $table->json('validation_payload');
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
            $table->index(['is_valid', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_messages');
    }
};
