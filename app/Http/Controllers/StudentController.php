<?php

namespace App\Http\Controllers;

use App\Models\SinhVien;
use App\Models\DeTai;
use Illuminate\Http\Request;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Evaluation50Export;
use App\Exports\ReviewAssignmentExport;
use App\Exports\CommitteeAssignmentExport;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        return SinhVien::with(['giangVienHuongDan', 'deTai'])->get()->map(function($s) {
            return [
                'mssv'               => $s->MSSV,
                'name'               => $s->Ho_va_Ten,
                'Lop'                => $s->Lop,
                'group'              => $s->Nhom,
                'topic'              => $s->MaDT ? $s->deTai->TenDeTai : '',
                'committee'          => $s->MaDT ? $s->deTai->MaHD : '',
                'MaDT'               => $s->MaDT,
                'email'              => $s->email,
                'phone'              => $s->sdt,
                'lecturer'           => $s->giangVienHuongDan ? $s->giangVienHuongDan->Ho_va_Ten : '',
                'status'             => $s->deTai ? $s->deTai->TrangThai : '-',
                'score'              => $s->Diem,
                'note'               => $s->GhiChu,
            ];
        });
    }

    public function show(SinhVien $student)
    {
        return $student->load(['giangVienHuongDan', 'deTai']);
    }

    public function getStudentsByTeacher(Request $request, $MaGV)
    {
        return SinhVien::where('Giang_vien_huong_dan', $MaGV)->get()->map(function($s) {
            return [
                'mssv'               => $s->MSSV,
                'name'               => $s->Ho_va_Ten,
                'Lop'                => $s->Lop,
                'group'              => $s->Nhom,
                'code'               => $s->MaDT, 
                'topic'              => $s->deTai ? $s->deTai->TenDeTai : '',
                'description'        => $s->deTai ? $s->deTai->MoTa : '',
                'email'              => $s->email,
                'phone'              => $s->sdt,
                'lecturer'           => $s->giangVienHuongDan ? $s->giangVienHuongDan->Ho_va_Ten : '',
                'status'             => $s->deTai ? $s->deTai->TrangThai : '-',
                'score'              => $s->Diem,
                'note'               => $s->GhiChu,
            ];
        });
    }
    public function getStudentsByReviewer(Request $request, $MaGV)
    {
        return SinhVien::whereHas('deTai', function ($q) use ($MaGV) {
            $q->where('MaGVPB', $MaGV);
        })
        ->with('deTai')
        ->get()
        ->map(function ($s) {
            return [
                'mssv'  => $s->MSSV,
                'name'  => $s->Ho_va_Ten,
                'group' => $s->Nhom,
                'MaDT'  => $s->MaDT,
                'TenDT' => $s->deTai?->TenDeTai,
            ];
        });
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'MSSV'                 => 'required|string|unique:SinhVien,MSSV',
            'Ho_va_Ten'            => 'required|string|max:120',
            'email'                => 'nullable|email|unique:SinhVien,email',
            'sdt'                  => 'nullable|string|max:15',
            'Lop'                  => 'nullable|string|max:50',
            'Nhom'                 => 'nullable|string|max:50',
            'MaDT'                 => 'nullable|string|exists:DeTai,MaDT',
            'Giang_vien_huong_dan' => 'nullable|string|exists:GiangVien,MaGV',
        ]);

        return SinhVien::create($data);
    }

   public function update(Request $request, $MSSV)
    {
        $student = SinhVien::where('MSSV', $MSSV)->firstOrFail();

        $data = $request->validate([
            'Ho_va_Ten'            => 'required|string|max:120',
            'email'                => 'nullable|email|unique:SinhVien,email,' . $student->MSSV . ',MSSV',
            'sdt'                  => 'nullable|string|max:15',
            'Lop'                  => 'nullable|string|max:50',
            'Nhom'                 => 'nullable|string|max:50',
            'MaDT'                 => 'nullable|string|exists:DeTai,MaDT',
            'Giang_vien_huong_dan' => 'nullable|string|exists:GiangVien,MaGV',
        ]);

        $student->update($data);

        return $student->load(['giangVienHuongDan', 'deTai']);
    }

    public function updateScore(Request $request)
    {
        $data = $request->validate([
            'Diem' => 'required|numeric|min:0|max:100',
            'MSSV' => 'required|string|exists:SinhVien,MSSV',
        ]);
        $MSSV = $request->MSSV;
        $student = SinhVien::where('MSSV', $MSSV)->firstOrFail();
        $student->update($data);
        return $student->load(['giangVienHuongDan', 'deTai']);
    }

    public function updateNote(Request $request)
    {
        $data = $request->validate([
            'GhiChu' => 'nullable|string|max:255',
            'MSSV' => 'required|string|exists:SinhVien,MSSV',
        ]);
        $MSSV = $request->MSSV;
        $student = SinhVien::where('MSSV', $MSSV)->firstOrFail();
        $student->update($data);
        return $student->load(['giangVienHuongDan', 'deTai']);
    }


    public function edit(Request $request, $MSSV)
    {
        $student = SinhVien::where('MSSV', $MSSV)->firstOrFail();
        $data = $request->validate([
            'Giang_vien_huong_dan' => 'required|string|exists:GiangVien,MaGV'
        ]);
        $student->update($data);
        return $student->load(['giangVienHuongDan', 'deTai']);
    }

    public function updateStudentGroup(Request $request)
    {
        $data = $request->validate([
            'mssv' => 'required|string|exists:SinhVien,MSSV',
            'group_number' => 'required|integer|min:1',
        ]);

        $mssv = $request->mssv;
        $groupNumber = $request->group_number;

        $student = SinhVien::where('MSSV', $mssv)->first();

        if (!$student) {
            return response()->json(['error' => 'Sinh viên không tồn tại'], 404);
        }

        $generatedGroup = $student->Giang_vien_huong_dan . '-' . $groupNumber;

        $sameGroup = SinhVien::where('Nhom', $generatedGroup)->get();

        if ($sameGroup->count() === 0) {
            $student->Nhom = $generatedGroup;
            $student->save();
            return response()->json(['success' => true]);
        }

        if ($sameGroup->count() >= 5) {
            return response()->json([
                'error' => 'Nhóm này đã đủ 5 thành viên!'
            ], 400);
        }

        $student->Nhom = $generatedGroup;
        $student->save();

        return response()->json(['success' => true]);
    }

    public function updateStudentGroups(Request $request)
{
    $request->validate([
        'updates' => 'required|array',
        'updates.*.mssv' => 'required|string|exists:SinhVien,MSSV',
        'updates.*.group_number' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();
    try {
        foreach ($request->updates as $item) {
            $student = SinhVien::where('MSSV', $item['mssv'])->first();
            $generatedGroup = $student->Giang_vien_huong_dan . '-' . $item['group_number'];

            // Reuse the same 5‑member limit logic from updateStudentGroup
            $sameGroup = SinhVien::where('Nhom', $generatedGroup)->get();
            if ($sameGroup->count() >= 5 && !$sameGroup->contains('MSSV', $student->MSSV)) {
                throw new \Exception("Nhóm {$item['group_number']} đã đủ 5 thành viên.");
            }

            $student->Nhom = $generatedGroup;
            $student->save();
        }
        DB::commit();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

    public function updateAllEvaluations(Request $request)
{
    $request->validate([
        'updates' => 'required|array',
        'updates.*.mssv' => 'required|string|exists:SinhVien,MSSV',
        'updates.*.score' => 'nullable|numeric|min:0|max:100',
        'updates.*.note' => 'nullable|string|max:255',
    ]);

    DB::beginTransaction();
    try {
        foreach ($request->updates as $item) {
            $student = SinhVien::where('MSSV', $item['mssv'])->first();
            
            // Only update fields that are present in the request
            if (array_key_exists('score', $item) && $item['score'] !== null) {
                $student->Diem = $item['score'];
            }
            if (array_key_exists('note', $item) && $item['note'] !== null) {
                $student->GhiChu = $item['note'];
            }
            $student->save();
        }
        DB::commit();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Cập nhật thất bại: ' . $e->getMessage()], 500);
    }
}

    public function updateStudentTopic(Request $request)
    {
        $data = $request->validate([
            'mssv' => 'required|string|exists:SinhVien,MSSV',
            'MaDT' => 'required|string|exists:DeTai,MaDT',
        ]);

        $mssv = $request->mssv;
        $MaDT = $request->MaDT;

        $student = SinhVien::where('MSSV', $mssv)->first();

        if (!$student) {
            return response()->json(['error' => 'Sinh viên không tồn tại'], 404);
        }

        $student->MaDT = $MaDT;
        $student->save();

        return response()->json(['success' => true]);
    }



    public function destroy(Request $request)
    {
        $student = SinhVien::where('MSSV', $request->mssv)->firstOrFail();
        return $student->delete();
    }

    public function export()
    {
        return Excel::download(new StudentsExport, 'DSSV.xlsx');
    }
    public function exportEvaluation50()
    {
        return Excel::download(new Evaluation50Export(),'DanhGia_50.xlsx');
    }
    public function exportReviewAssignment()
    {
        return Excel::download(new ReviewAssignmentExport(),'PhanCong_PhanBien.xlsx');
    }
    public function exportCommitteeAssignment()
    {
        return Excel::download(new CommitteeAssignmentExport(),'PhanCong_HoiDong.xlsx');
    }

    public function dashboardStats()
    {
        $svDaCoGV = SinhVien::whereNotNull('Giang_vien_huong_dan')->count();

        $svChuaCoGV = SinhVien::whereNull('Giang_vien_huong_dan')->count();

        $detaiStats = DeTai::select('TrangThai')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('TrangThai')
            ->pluck('total', 'TrangThai');

        return response()->json([
            'sinh_vien' => [
                'da_co_gv'   => $svDaCoGV,
                'chua_co_gv'=> $svChuaCoGV,
            ],
            'de_tai' => [
                'tiep_tuc' => $detaiStats['Được tiếp tục'] ?? 0,
                'dinh_chi' => $detaiStats['Đình Chỉ'] ?? 0,
                'xin_hoan' => $detaiStats['Xin hoãn'] ?? 0,
            ]
        ]);
    }
        
    public function layDataSinhVienTheoMSSV(Request $request, $MSSV)
    {
        $student = SinhVien::where('MSSV', $MSSV)->firstOrFail();
        $student->load(['giangVienHuongDan', 'deTai']);
        return [
            'mssv'     => $student->MSSV,
            'ten'     => $student->Ho_va_Ten,
            'Lop'      => $student->Lop,
            'nhom'    => $student->Nhom,
            'tenDeTai'    => $student->deTai ? $student->deTai->TenDeTai : '',
            'GVHD' => $student->giangVienHuongDan ? $student->giangVienHuongDan->Ho_va_Ten : '',
            'email'    => $student->email,
            'sdt'    => $student->sdt,
        ];
    }


}