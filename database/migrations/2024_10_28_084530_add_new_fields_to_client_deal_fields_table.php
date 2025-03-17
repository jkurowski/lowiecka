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
        Schema::table('client_deal_fields', function (Blueprint $table) {
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('storage_id')->nullable();
            $table->unsignedBigInteger('parking_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_deal_fields', function (Blueprint $table) {
            $table->dropColumn('property_id');
            $table->dropColumn('storage_id');
            $table->dropColumn('parking_id');
        });
    }
};
