
// database/migrations/2026_09_12_000000_create_terms_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chapter_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('term');
            $table->text('definition');
            $table->timestamps();

            $table->index(['book_id', 'chapter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terms');
    }
};
