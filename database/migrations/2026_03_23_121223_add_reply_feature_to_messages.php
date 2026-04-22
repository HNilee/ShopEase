<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('messages', function (Blueprint $table) {
            // Kolom untuk fitur Reply
            if (!Schema::hasColumn('messages', 'reply_to_id')) {
                $table->unsignedBigInteger('reply_to_id')->nullable();
                $table->foreign('reply_to_id')->references('id')->on('messages')->nullOnDelete();
            }
            // Pastikan kolom CS dan Read ada
            if (!Schema::hasColumn('messages', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable();
            }
            if (!Schema::hasColumn('messages', 'is_read')) {
                $table->boolean('is_read')->default(false);
            }
        });
    }

    public function down() {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn(['reply_to_id', 'customer_id', 'is_read']);
        });
    }
};