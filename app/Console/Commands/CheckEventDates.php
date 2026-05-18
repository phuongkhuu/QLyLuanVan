<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ThoiGian;
use App\Models\User;
use App\Notifications\EventApproachingNotification;
use Carbon\Carbon;

class CheckEventDates extends Command
{
    // Tên lệnh để chạy trong terminal
    protected $signature = 'app:check-event-dates';
    
    // Cập nhật lại mô tả của lệnh
    protected $description = 'Kiểm tra và thông báo ngày bắt đầu và ngày kết thúc (trước 1 ngày)';

    public function handle()
    {
        // 1. Lấy mốc thời gian là ngày mai (cộng thêm 1 ngày so với hiện tại)
        $ngayMai = Carbon::now()->addDays(1)->toDateString(); 

        $teachers = User::where('role', 'GiangVien')->get();
        $hasNotification = false;

        if ($teachers->isEmpty()) {
            $this->info('Không có giáo viên nào trong hệ thống.');
            return;
        }

        // 2. KIỂM TRA SỰ KIỆN SẮP BẮT ĐẦU (VÀO NGÀY MAI)
        $eventsStarting = ThoiGian::whereDate('NgayBatDau', $ngayMai)->get();
        foreach ($eventsStarting as $event) {
            foreach ($teachers as $teacher) {
                $teacher->notify(new EventApproachingNotification($event->TenSuKien, $event->NgayBatDau, 'start'));
            }
            $hasNotification = true;
            $this->info("Đã báo bắt đầu cho sự kiện: {$event->TenSuKien}");
        }

        // 3. KIỂM TRA SỰ KIỆN SẮP KẾT THÚC / HẠN CHÓT (VÀO NGÀY MAI)
        $eventsEnding = ThoiGian::whereDate('NgayKetThuc', $ngayMai)->get();
        foreach ($eventsEnding as $event) {
            foreach ($teachers as $teacher) {
                $teacher->notify(new EventApproachingNotification($event->TenSuKien, $event->NgayKetThuc, 'end'));
            }
            $hasNotification = true;
            $this->info("Đã báo hạn chót cho sự kiện: {$event->TenSuKien}");
        }

        // In ra console nếu không có gì xảy ra
        if (!$hasNotification) {
            $this->info('Hôm nay không có sự kiện nào sắp bắt đầu hoặc sắp kết thúc vào ngày mai.');
        }
    }
}