<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique();
            $t->string('tiket');
            $t->unsignedTinyInteger('qty');
            $t->unsignedInteger('total');
            $t->string('nama')->nullable();
            $t->string('email')->nullable();
            $t->string('phone')->nullable();
            $t->string('metode')->nullable();
            $t->string('status')->default('pending');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};