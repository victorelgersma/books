
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->unsignedSmallInteger('year_published')->nullable();
            $table->unsignedSmallInteger('year_read')->nullable();
            $table->unsignedTinyInteger('month_read')->nullable(); // 1-12
            $table->timestamps();

            $table->index(['year_read', 'month_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
