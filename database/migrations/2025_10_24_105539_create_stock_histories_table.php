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
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->enum('adjustment_type', [
                'purchase',     // Achat de stock
                'sale',         // Vente
                'return',       // Retour client
                'damage',       // Produit endommagé
                'loss',         // Perte
                'transfer_in',  // Transfert entrant
                'transfer_out', // Transfert sortant
                'adjustment'    // Ajustement manuel
            ])->default('adjustment');
            $table->integer('quantity_before');
            $table->integer('quantity_change');
            $table->integer('quantity_after');
            $table->foreignUuid('changed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
