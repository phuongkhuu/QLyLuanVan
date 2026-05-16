<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\SinhVien;
use App\Models\GiangVien;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function messages(Request $request, string $contactType, string $contactId)
    {
        $currentUser = auth()->user();
        $currentParticipant = $this->resolveParticipant($currentUser);
        $contact = $this->resolveContact($contactType, $contactId);
        $this->authorizeChat($currentParticipant, $contact);

        $messages = Message::where(function ($q) use ($currentParticipant, $contact) {
            $q->where('sender_id', $currentParticipant->getKey())
              ->where('sender_type', $currentParticipant->getMorphClass())
              ->where('receiver_id', $contact->getKey())
              ->where('receiver_type', $contact->getMorphClass());
        })->orWhere(function ($q) use ($currentParticipant, $contact) {
            $q->where('sender_id', $contact->getKey())
              ->where('sender_type', $contact->getMorphClass())
              ->where('receiver_id', $currentParticipant->getKey())
              ->where('receiver_type', $currentParticipant->getMorphClass());
        })->orderBy('created_at')->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_type' => 'required|in:SinhVien,GiangVien',
            'receiver_id'   => 'required|string',
            'body'          => 'required|string|max:2000',
        ]);

        $currentUser = auth()->user();
        $sender = $this->resolveParticipant($currentUser);
        $receiver = $this->resolveContact($request->receiver_type, $request->receiver_id);
        $this->authorizeChat($sender, $receiver);

        $message = Message::create([
            'sender_id'   => $sender->getKey(),
            'sender_type' => $sender->getMorphClass(),   
            'receiver_id' => $receiver->getKey(),
            'receiver_type' => $receiver->getMorphClass(), 
            'body'        => $request->body,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }

    private function authorizeChat($participantA, $participantB)
    {
        if ($participantA instanceof SinhVien) {
            if (! $participantB instanceof GiangVien) {
                abort(403, 'Bạn chỉ có thể nhắn tin với giảng viên hướng dẫn.');
            }
            if ($participantA->Giang_vien_huong_dan !== $participantB->MaGV) {
                abort(403, 'Giảng viên này không phải là GVHD của bạn.');
            }
        } elseif ($participantA instanceof GiangVien) {
            if (! $participantB instanceof SinhVien) {
                abort(403, 'Bạn chỉ có thể nhắn tin với sinh viên của mình.');
            }
            if ($participantB->Giang_vien_huong_dan !== $participantA->MaGV) {
                abort(403, 'Sinh viên này không thuộc hướng dẫn của bạn.');
            }
        } else {
            abort(403, 'Vai trò không hợp lệ.');
        }
    }

    private function resolveParticipant($user)
    {
        return $user->sinhVien ?? $user->giangVien
            ?? abort(403, 'Không tìm thấy hồ sơ sinh viên/giảng viên.');
    }

    private function resolveContact(string $type, string $id)
    {
        return match ($type) {
            'SinhVien'  => SinhVien::findOrFail($id),
            'GiangVien' => GiangVien::findOrFail($id),
            default     => abort(404, 'Loại người dùng không hợp lệ.'),
        };
    }
}
