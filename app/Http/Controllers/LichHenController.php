<?php

namespace App\Http\Controllers;

use App\Models\LichHenSV;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LichHenController extends Controller
{
    public function index(): JsonResponse
    {
        $lichHen = LichHenSV::with(['sinhvien', 'detai'])
            // ->where('MaGV', $maGV)
            ->orderBy('ThoiGianGap', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'          => $item->id,
                    'MSSV'        => $item->MSSV,
                    'studentName' => $item->sinhvien?->Ho_va_Ten ?? '',
                    'topic'       => $item->detai?->TenDeTai ?? '',
                    'MaDT'        => $item->MaDT,
                    'ThoiGianGap' => $item->ThoiGianGap,
                    'DiaDiem'     => $item->DiaDiem,
                    'TrangThai'   => $item->TrangThai,
                    'LoaiLich'    => $item->LoaiLich,
                    'GhiChu'      => $item->GhiChu,
                ];
            });

        return response()->json($lichHen);
    }

    /**
     * Tạo lịch hẹn mới.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'MSSV'        => 'required|string|exists:SinhVien,MSSV',
            'MaDT'        => 'nullable|string|exists:DeTai,MaDT',
            'ThoiGianGap' => 'required|date',
            'DiaDiem'     => 'required|string',
            'LoaiLich'    => 'required|integer|in:1,2',   // 1 = Hướng dẫn, 2 = Phản biện
            'TrangThai'   => 'sometimes|string',
            'GhiChu'      => 'nullable|string',
        ]);

        // Dùng mã giảng viên đăng nhập
        // $maGV = auth()->user()->giangvien->MaGV;

        $lich = LichHenSV::create([
            'MSSV'        => $validated['MSSV'],
            'MaDT'        => $validated['MaDT'] ?? null,
            'MaGV'        => 'GV01',          // thay bằng $maGV
            'ThoiGianGap' => $validated['ThoiGianGap'],
            'DiaDiem'     => $validated['DiaDiem'],
            'TrangThai'   => $validated['TrangThai'] ?? 'Chờ xác nhận',
            'LoaiLich'    => $validated['LoaiLich'],
            'GhiChu'      => $validated['GhiChu'] ?? null,
        ]);

        // Trả về đối tượng vừa tạo kèm quan hệ
        return response()->json($lich->load(['sinhvien', 'detai']), 201);
    }

    /**
     * Xoá lịch hẹn.
     */
    public function destroy($id): JsonResponse
    {
        $lich = LichHenSV::findOrFail($id);
        $lich->delete();

        return response()->json(['message' => 'Xoá lịch thành công']);
    }
}