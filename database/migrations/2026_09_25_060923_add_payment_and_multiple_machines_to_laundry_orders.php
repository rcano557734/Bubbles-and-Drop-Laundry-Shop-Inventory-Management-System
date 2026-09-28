<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laundry_orders', function (Blueprint $table) {
            $table->string('payment_status')
                ->default('Unpaid')
                ->after('status');

            $table->dateTime('paid_at')
                ->nullable()
                ->after('payment_status');
        });

        Schema::create('laundry_order_machine', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laundry_order_id')
                ->constrained('laundry_orders')
                ->cascadeOnDelete();

            $table->foreignId('machine_id')
                ->constrained('machines')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'laundry_order_id',
                'machine_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_order_machine');

        Schema::table('laundry_orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'paid_at',
            ]);
        });
    }
};