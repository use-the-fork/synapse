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
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->string('role');
            $table->text('content')->nullable();
            $table->string('name')->nullable();
            $table->string('tool_call_id')->nullable();
            $table->json('tool_calls')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};