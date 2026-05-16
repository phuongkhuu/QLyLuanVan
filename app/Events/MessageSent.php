<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('sender', 'receiver');
    }

    public function broadcastOn()
    {
        $participants = [
            $this->message->sender_type . '.' . $this->message->sender_id,
            $this->message->receiver_type . '.' . $this->message->receiver_id,
        ];
        sort($participants);
        return new PrivateChannel('chat.' . implode('.', $participants));
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }

    public function broadcastWith()
    {
        return [
            'id'            => $this->message->id,
            'sender_id'     => $this->message->sender_id,
            'sender_type'   => $this->message->sender_type,
            'receiver_id'   => $this->message->receiver_id,
            'receiver_type' => $this->message->receiver_type,
            'body'          => $this->message->body,
            'created_at'    => $this->message->created_at->toISOString(),
        ];
    }
}