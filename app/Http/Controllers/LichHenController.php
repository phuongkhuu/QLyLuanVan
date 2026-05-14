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
            ->where('MaGV', auth()->user()->giangvien->MaGV)
            ->orderBy('ThoiGianGap', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'          => $item->id,
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
            'ThoiGianGap' => 'required|date',
            'DiaDiem'     => 'required|string',
            'LoaiLich'    => 'required|integer|in:1,2',   // 1 = Hướng dẫn, 2 = Phản biện
            'TrangThai'   => 'sometimes|string',
            'GhiChu'      => 'nullable|string',
        ]);


        $lich = LichHenSV::create([
            'MaGV'        => auth()->user()->giangvien->MaGV,          // thay bằng $maGV
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
    * Cập nhật lịch hẹn.
    */

    public function update(Request $request, $id)
    {
        $lich = LichHenSV::findOrFail($id);

        $validated = $request->validate([
            'ThoiGianGap' => 'sometimes|date',
            'DiaDiem'     => 'sometimes|string',
            'LoaiLich'    => 'sometimes|integer|in:1,2',
            'TrangThai'   => 'sometimes|string',
            'GhiChu'      => 'nullable|string',
        ]);

        $lich->update($validated);
    }

    /**
     * Xoá lịch hẹn.
     */
    public function destroy(Request $request, $id)
    {
        $lich = LichHenSV::findOrFail($id);
        $lich->delete();
    }
}