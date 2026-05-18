<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventApproachingNotification extends Notification
{
    use Queueable;

    protected $tenSuKien;
    protected $thoiGian;
    protected $loaiThongBao; // 'start' hoặc 'end'

    public function __construct($tenSuKien, $thoiGian, $loaiThongBao)
    {
        $this->tenSuKien = $tenSuKien;
        $this->thoiGian = $thoiGian;
        $this->loaiThongBao = $loaiThongBao;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // Phân loại nội dung tùy theo Bắt đầu hay Kết thúc
        if ($this->loaiThongBao === 'start') {
            $title = 'Sắp đến thời gian bắt đầu';
            $message = "Sự kiện: {$this->tenSuKien} sẽ BẮT ĐẦU vào lúc " . $this->thoiGian->format('d/m/Y H:i');
            $type = 'info';
        } else {
            $title = 'Sắp đến hạn chót!';
            $message = "Sự kiện: {$this->tenSuKien} sẽ KẾT THÚC (hạn chót) vào lúc " . $this->thoiGian->format('d/m/Y H:i');
            $type = 'warning';
        }

        return [
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'time' => now()->toDateTimeString(),
        ];
    }
}