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
    Schema::create('app_types', function (Blueprint $table) {
    $table->id();
    $table->foreignId('app_list_id')
          ->constrained('app_lists')
          ->onDelete('cascade');
    $table->enum('store_type', ['play_store', 'app_store', 'others']);
    $table->string('url');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_types');
    }
};
