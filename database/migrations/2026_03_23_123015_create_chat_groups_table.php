<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('chat_groups')) {
            Schema::create('chat_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('chat_group_user')) {
            Schema::create('chat_group_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('chat_group_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('messages', 'chat_group_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->foreignId('chat_group_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['chat_group_id']);
            $table->dropColumn('chat_group_id');
        });
        Schema::dropIfExists('chat_group_user');
        Schema::dropIfExists('chat_groups');
    }
};