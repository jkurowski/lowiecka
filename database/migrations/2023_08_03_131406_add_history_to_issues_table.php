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
        Schema::table('issues', function (Blueprint $table) {
            $table->text('history')->nullable();
        });
    }

    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn('history');
        });
    }
};
