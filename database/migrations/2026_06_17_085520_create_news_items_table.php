<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_items', function (Blueprint $table) {
            $table->id();
            $table->enum('badge', ['investment', 'repairFund', 'repair', 'general'])->default('general');
            $table->date('date');
            $table->string('title_sk');
            $table->string('title_en');
            $table->string('summary_sk');
            $table->string('summary_en');
            $table->text('content_sk')->nullable();
            $table->text('content_en')->nullable();
            $table->boolean('pinned')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_items');
    }
};
