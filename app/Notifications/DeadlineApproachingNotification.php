<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DeadlineApproachingNotification extends Notification
{
    use Queueable;

    protected $tenSuKien;
    protected $ngayKetThuc;

    public function __construct($tenSuKien, $ngayKetThuc)
    {
        $this->tenSuKien = $tenSuKien;
        $this->ngayKetThuc = $ngayKetThuc;
    }

    public function via($notifiable)
    {
        return ['database']; // Chỉ lưu vào Database để hiển thị trên web
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Sắp đến hạn chót!',
            'message' => "Sự kiện: {$this->tenSuKien} sẽ kết thúc vào " . $this->ngayKetThuc->format('d/m/Y H:i'),
            'type' => 'warning',
            'time' => now()->toDateTimeString(),
        ];
    }
}