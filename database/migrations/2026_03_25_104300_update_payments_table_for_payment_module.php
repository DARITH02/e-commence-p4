<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update payments table
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'provider')) {
                $table->string('provider')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('payments', 'response_json')) {
                // Rename payload to response_json if payload exists
                if (Schema::hasColumn('payments', 'payload')) {
                    $table->renameColumn('payload', 'response_json');
                } else {
                    $table->json('response_json')->nullable()->after('status');
                }
            }
            if (!Schema::hasColumn('payments', 'method')) {
                // Keep payment_method for legacy but add method as requested
                $table->string('method')->nullable()->after('order_id');
            }
        });

        // Update orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'total')) {
                // Use total_amount as total
                $table->decimal('total', 15, 2)->virtualAs('total_amount')->nullable()->after('total_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['provider', 'method']);
            if (Schema::hasColumn('payments', 'response_json')) {
                $table->renameColumn('response_json', 'payload');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total');
        });
    }
};
