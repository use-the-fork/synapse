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
        Schema::create('agents', function (Blueprint $table) {

            $table->uuid('id');
            $table->string('type');
            $table->string('model')->default('gpt-4-turbo');
            $table->text('prompt');
            $table->text('description')->nullable();
            $table->json('tools')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
