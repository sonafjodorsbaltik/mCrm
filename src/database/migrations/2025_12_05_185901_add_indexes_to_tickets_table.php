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
        Schema::table('tickets', function (Blueprint $table) {
            // Index for status filtering
            $table->index('status');
            
            // Index for date sorting/filtering
            $table->index('created_at');
            
            // Composite index for customer queries with date sorting
            $table->index(['customer_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['tickets_status_index']);
            $table->dropIndex(['tickets_created_at_index']);
            $table->dropIndex(['tickets_customer_id_created_at_index']);
        });
    }
};
