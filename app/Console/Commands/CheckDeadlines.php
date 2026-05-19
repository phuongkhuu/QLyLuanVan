<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ThoiGian;
use App\Models\User;
use App\Notifications\DeadlineApproachingNotification;
use Carbon\Carbon;

class CheckDeadlines extends Command
{
    protected $signature = 'app:check-deadlines';
    protected $description = 'Kiểm tra hạn chót của các sự kiện và thông báo cho giảng viên';

    public function handle()
    {
        // Lấy ngày hiện tại + 3 ngày (Thông báo trước 3 ngày)
        $targetDate = Carbon::now()->addDays(3)->toDateString();

        // Tìm các sự kiện có Ngày kết thúc trùng với targetDate
        $events = ThoiGian::whereDate('NgayKetThuc', $targetDate)->get();

        if ($events->count() > 0) {
            // Lấy tất cả user là Giảng viên
            $teachers = User::where('role', 'GiangVien')->get();

            foreach ($events as $event) {
                foreach ($teachers as $teacher) {
                    $teacher->notify(new DeadlineApproachingNotification($event->TenSuKien, $event->NgayKetThuc));
                }
            }
            $this->info('Đã gửi thông báo cho giáo viên.');
        } else {
            $this->info('Không có sự kiện nào sắp đến hạn.');
        }
    }
}