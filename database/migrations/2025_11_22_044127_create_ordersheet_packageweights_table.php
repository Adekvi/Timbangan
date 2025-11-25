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
        Schema::create('ordersheet_packageweights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_package')
                ->constrained('ordersheet_packages')
                ->onDelete('cascade');
            $table->decimal('weight', 10, 2)->nullable();
            $table->string('no_package')->nullable();
            $table->decimal('rasio_batas_beban_min', 8, 2)->nullable();
            $table->decimal('rasio_batas_beban_max', 8, 2)->nullable();
            $table->timestamp('waktu_timbang')->useCurrent();
            $table->enum('status', ['Pending', 'Success', 'Rejected'])->default('Pending');
            $table->timestamps();

            $table->index(['id_package', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordersheet_packageweights');
    }
};
