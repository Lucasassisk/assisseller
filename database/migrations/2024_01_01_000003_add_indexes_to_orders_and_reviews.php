<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('payment_status');
            $table->index('order_status');
            $table->index(['payment_status', 'created_at']);
            $table->index('customer_email');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('approved');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['order_status']);
            $table->dropIndex(['payment_status', 'created_at']);
            $table->dropIndex(['customer_email']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['approved']);
        });
    }
};
