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
        Schema::create('device_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->foreignId('firmware_id')->constrained('firmwares')->onDelete('cascade');

            $table->enum('status', ['pending', 'downloading', 'installing', 'success', 'failed'])
                ->default('pending');
            
            $table->timestamp('pushed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();

            // satu device hanya boleh punya satu update yang pending/active
            $table->unique('device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_updates');
    }
};
