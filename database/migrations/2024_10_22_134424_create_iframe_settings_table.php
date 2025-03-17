<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iframe_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investment_id');
            $table->string('bg_color')->default('#ffffff');
            $table->string('text_color')->default('#000000');
            $table->string('font_family')->default('Arial');
            $table->integer('font_size')->default(16);
            $table->text('custom_css')->nullable();
            $table->integer('preview_width')->default(100);
            $table->integer('preview_height')->default(500);
            $table->string('link_color')->default('#000000');
            $table->string('link_hover_color')->default('#000000');
            $table->string('box_offer_bg_color')->default('#ffffff');
            $table->string('box_offer_margin')->default('10px');
            $table->string('box_offer_padding')->default('10px');
            $table->integer('box_offer_title_font_size')->default(16);
            $table->timestamps();

            $table->foreign('investment_id')->references('id')->on('investments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iframe_settings');
    }
};
