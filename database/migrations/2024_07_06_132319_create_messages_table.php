<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('agent_memory_id')->constrained('agent_memories')->onDelete('cascade');
            $table->string('role');
            $table->text('content')->nullable();
            $table->string('tool_name')->nullable();
            $table->text('tool_arguments')->nullable();
            $table->string('tool_call_id')->nullable();
            $table->text('tool_content')->nullable();
            $table->json('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
