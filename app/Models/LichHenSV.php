<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichHenSV extends Model
{
    use HasFactory;

    // Chỉ định đúng tên bảng trong database
    protected $table = 'lichhensv';

    // Các cột được phép thêm/sửa dữ liệu hàng loạt
    protected $fillable = [
        'MSSV', 
        'MaDT', 
        'MaGV', 
        'ThoiGianGap', 
        'DiaDiem', 
        'TrangThai', 
        'LoaiLich', 
        'GhiChu'
    ];

    // Mối quan hệ: 1 Lịch hẹn thuộc về 1 Sinh viên
    public function sinhvien()
    {
        // belongsTo(Tên_Model, 'cột_khóa_ngoại_ở_bảng_hiện_tại', 'cột_khóa_chính_ở_bảng_đích')
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    // Mối quan hệ: 1 Lịch hẹn thuộc về 1 Đề tài
    public function detai()
    {
        return $this->belongsTo(DeTai::class, 'MaDT', 'MaDT');
    }

    // Mối quan hệ: 1 Lịch hẹn do 1 Giảng viên tạo
    public function giangvien()
    {
        return $this->belongsTo(GiangVien::class, 'MaGV', 'MaGV');
    }
}