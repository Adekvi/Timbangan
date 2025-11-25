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
        Schema::create('ordersheet_packages', function (Blueprint $table) {
           $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('leather_type')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->json('hardware_details')->nullable();
            $table->string('stitching_type')->nullable();
            $table->string('lining_material')->nullable();
            // $table->integer('price')->default(0);
            // $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordersheet_packages');
    }
};
