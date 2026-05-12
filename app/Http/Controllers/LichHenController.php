<?php

namespace App\Http\Controllers;

use App\Models\LichHenSV;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LichHenController extends Controller
{
    // Xem danh sách
    public function index()
    {
        // $maGvHienTai = auth()->user()->giangvien->MaGV; 
        
        $danhSachLichHen = LichHenSV::with(['sinhvien', 'detai'])
            // ->where('MaGV', $maGvHienTai)
            ->orderBy('ThoiGianGap', 'desc')
            ->get();

        $danhSachSinhVien = SinhVien::with('detai')->get();

        return Inertia::render('Teachers/components/AppointmentView', [
            'lichHenData' => $danhSachLichHen,
            'sinhVienData' => $danhSachSinhVien
        ]);
    }

    // Thêm lịch mới
    public function store(Request $request)
    {
        $request->validate([
            'MSSV' => 'required',
            'ThoiGianGap' => 'required|date',
            'DiaDiem' => 'required',
            'LoaiLich' => 'required|integer'
        ]);

        LichHenSV::create([
            'MSSV' => $request->MSSV,
            'MaDT' => $request->MaDT,
            'MaGV' => 'GV01', // Thay bằng mã GV thực tế từ auth()->user()
            'ThoiGianGap' => $request->ThoiGianGap,
            'DiaDiem' => $request->DiaDiem,
            'TrangThai' => $request->TrangThai,
            'LoaiLich' => $request->LoaiLich,
            'GhiChu' => $request->GhiChu
        ]);

        return redirect()->back()->with('success', 'Tạo lịch thành công');
    }

    // Xóa lịch
    public function destroy($id)
    {
        LichHenSV::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Xóa thành công');
    }
}