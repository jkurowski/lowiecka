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
        Schema::create('client_deal_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->integer('status');
            $table->string('deal_additional')->nullable();
            $table->unsignedBigInteger('investment_id');
            $table->integer('room')->nullable();
            $table->decimal('area', 10, 2)->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->integer('purpose');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('investment_id')->references('id')->on('investments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_deal_fields');
    }
};
