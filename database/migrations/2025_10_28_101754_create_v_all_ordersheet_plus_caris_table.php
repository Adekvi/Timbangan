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
        Schema::create('v_all_ordersheet_plus_caris', function (Blueprint $table) {
            $table->id();
            $table->string('Order_code')->index();
            $table->string('Buyer')->nullable();
            $table->string('BuyMonth')->nullable();
            $table->string('PurchaseOrderNumber')->nullable();
            $table->string('POLineItemNumber')->nullable();
            $table->string('TradingCoPONumber')->nullable();
            $table->string('ProductCode')->nullable();
            $table->string('ProductName')->nullable();
            $table->string('ColorDescription')->nullable();
            $table->string('KJ')->nullable();
            $table->integer('MOC')->nullable()->default(0);
            $table->integer('Qty')->nullable()->default(0);
            $table->decimal('ActualFOB', 10, 2)->nullable();
            $table->string('DestinationCountry')->nullable();
            $table->string('FinalDestination')->nullable();
            $table->string('GAC')->nullable();
            $table->date('DocumentDate')->nullable();
            $table->string('SilhouetteDescription')->nullable();
            $table->text('cari')->nullable();
            $table->string('status')->nullable()->default('Null');
            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index('id');
            $table->index('DocumentDate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_all_ordersheet_plus_caris');
    }
};