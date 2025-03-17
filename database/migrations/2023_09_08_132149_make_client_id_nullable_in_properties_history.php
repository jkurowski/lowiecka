<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('properties_history', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('properties_history', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable(false)->change();
        });
    }
};
