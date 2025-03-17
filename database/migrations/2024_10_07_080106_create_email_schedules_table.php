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
        Schema::create('email_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('email_type');
            $table->string('email_address');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('scheduled_date')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('subject')->nullable();
            $table->longText('content')->nullable();
            $table->string('action')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('opened_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_schedules');
    }
};
