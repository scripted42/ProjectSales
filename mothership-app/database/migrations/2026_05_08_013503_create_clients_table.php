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
        Schema::create('clients', function (Blueprint $column) {
            $column->id();
            $column->string('domain')->unique();
            $column->string('site_name')->nullable();
            $column->string('plan')->default('regular'); // regular, pro
            $column->string('status')->default('active'); // active, suspended
            $column->date('expired_at')->nullable();
            $column->string('token')->nullable();
            $column->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
