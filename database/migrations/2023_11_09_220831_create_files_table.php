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
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type',30)->index();
            $table->string('title',350)->index();
            $table->string('name',50)->index();
            $table->string('ext',15)->index();
            $table->string('path',250)->index();
            $table->string('disk',50)->index();
            $table->bigInteger('size')->unsigned()->index();
            $table->longText('details')->default('{}');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
