<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('Pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'paid_at',
                'payment_reference',
            ]);
        });
    }
};
