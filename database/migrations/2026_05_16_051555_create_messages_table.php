<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id');          // MSSV or MaGV
            $table->string('sender_type');        // 'App\Models\SinhVien' or 'App\Models\GiangVien'
            $table->string('receiver_id');
            $table->string('receiver_type');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['sender_id', 'sender_type']);
            $table->index(['receiver_id', 'receiver_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
