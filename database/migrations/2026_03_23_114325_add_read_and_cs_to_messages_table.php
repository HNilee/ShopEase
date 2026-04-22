<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            // Menjadikan order_id boleh kosong (Untuk CS)
            $table->unsignedBigInteger('order_id')->nullable()->change();
            
            // Kolom penanda siapa yang chat CS
            if (!Schema::hasColumn('messages', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable();
            }
            
            // Kolom penanda Read Receipt (Centang Biru)
            if (!Schema::hasColumn('messages', 'is_read')) {
                $table->boolean('is_read')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['customer_id', 'is_read']);
        });
    }
};