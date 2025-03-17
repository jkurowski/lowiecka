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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('nip')->nullable();
            $table->string('pesel')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->string('id_issued_by')->nullable();
            $table->date('id_issued_date')->nullable();
            $table->string('is_company')->nullable();
            $table->string('company_name')->nullable();
            $table->string('regon')->nullable();
            $table->string('krs')->nullable();
            $table->string('address')->nullable();
            $table->string('exponent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('nip');
            $table->dropColumn('pesel');
            $table->dropColumn('id_type');
            $table->dropColumn('id_number');
            $table->dropColumn('id_issued_by');
            $table->dropColumn('id_issued_date');
            $table->dropColumn('is_company');
            $table->dropColumn('company_name');
            $table->dropColumn('regon');
            $table->dropColumn('krs');
            $table->dropColumn('address');
            $table->dropColumn('exponent');
        });
    }
};
