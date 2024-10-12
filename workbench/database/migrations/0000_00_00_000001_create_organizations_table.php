<?php

declare(strict_types=1);

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain');
            $table->string('email');
            $table->string('country_code');
            $table->string('city');
            $table->string('short_description');
            $table->string('status');
            $table->float('num_funding_rounds');
            $table->float('total_funding_usd');
            $table->date('founded_on');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
