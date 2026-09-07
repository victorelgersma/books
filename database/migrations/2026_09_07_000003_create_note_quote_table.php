<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_quote', function (Blueprint $table) {
            $table->foreignId('note_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->primary(['note_id', 'quote_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_quote');
    }
};
