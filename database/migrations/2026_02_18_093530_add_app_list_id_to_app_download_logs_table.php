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
        Schema::table('app_download_logs', function (Blueprint $table) {
            //
            $table->foreignId('app_list_id')->after('id')->nullable()->constrained('app_lists')->onDelete('set null');

            $table->index('app_list_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_download_logs', function (Blueprint $table) {
            //
            $table->dropForeign(['app_list_id']);
            $table->dropColumn('app_list_id');
        });
    }
};
