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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('telegram_chat_id')->nullable()->after('phone');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('telegram_chat_id')->nullable()->after('shipping_address_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'telegram_chat_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('telegram_chat_id');
        });
    }
};
