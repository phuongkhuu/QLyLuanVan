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
        Schema::create('lichhensv', function (Blueprint $table) {
            $table->id(); // Khóa chính tự tăng của bảng lịch hẹn
            
            // 1. Tạo các cột khóa ngoại (kiểu dữ liệu và độ dài phải khớp 100% với bảng gốc)
            $table->string('MSSV', 20);
            $table->string('MaDT', 50)->nullable(); // Có thể rỗng nếu hẹn nhưng chưa chốt đề tài
            $table->string('MaGV', 20);

            // 2. Các thông tin của lịch hẹn (Dựa theo giao diện UI của bạn)
            $table->dateTime('ThoiGianGap'); // Ngày và giờ gặp
            $table->string('DiaDiem', 255);  // Địa điểm gặp (VD: Phòng A, Google Meet)
            $table->string('TrangThai', 50)->default('Chờ xác nhận'); // VD: Chờ xác nhận, Đã xác nhận, Đã hủy
            $table->tinyInteger('LoaiLich')->comment('1: Lịch Hướng dẫn, 2: Lịch Phản biện'); // Dùng để phân loại 2 tab trên UI
            $table->text('GhiChu')->nullable(); // Cho phép ghi chú thêm
            
            $table->timestamps(); // Tạo 2 cột created_at và updated_at

            // 3. Thiết lập Ràng buộc Khóa ngoại (Foreign Keys)
            $table->foreign('MSSV')->references('MSSV')->on('sinhvien')->onDelete('cascade');
            $table->foreign('MaDT')->references('MaDT')->on('detai')->onDelete('set null');
            $table->foreign('MaGV')->references('MaGV')->on('giangvien')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lichhensv');
    }
};