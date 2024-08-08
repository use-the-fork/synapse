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
        Schema::create('assistants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model');
            $table->text('description')->nullable();
            $table->text('prompt');
            $table->json('tools')->nullable();
            $table->string('service')->default('openai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistants');
    }
};
