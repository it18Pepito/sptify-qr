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
        if (!Schema::hasTable('app_download_logs')) {
            Schema::create('app_download_logs', function (Blueprint $table) {
                $table->id();
                $table->timestamp('created_at')->useCurrent()->index();
                $table->string('store_code', 50)->nullable()->index();
                $table->string('campaign', 100)->nullable()->index();
                $table->string('ip', 45)->nullable()->index();
                $table->string('country', 100)->nullable();
                $table->char('country_alpha_2', 3)->nullable();
                $table->string('province', 100)->nullable();
                $table->string('regency', 100)->nullable();
                $table->string('district', 100)->nullable();
                $table->string('subdistrict', 100)->nullable();
                $table->text('street')->nullable();
                $table->string('postal_code', 20)->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->string('isp', 100)->nullable();
                $table->string('asn', 100)->nullable();
                $table->string('os', 50)->nullable();
                $table->string('os_version', 50)->nullable();
                $table->string('device_type', 50)->nullable();
                $table->string('browser', 50)->nullable();
                $table->boolean('is_in_app_browser')->default(false);
                $table->string('redirect_to', 50)->nullable();
                $table->string('result', 50)->default('success');
                $table->string('timezone', 50)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_download_logs');
    }
};
