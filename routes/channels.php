<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\SinhVien;
use App\Models\GiangVien;


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{type1}.{id1}.{type2}.{id2}', function ($user, $type1, $id1, $type2, $id2) {
    // Get the participant linked to the authenticated user
    $participant = $user->sinhVien ?? $user->giangVien;

    if (!$participant) return false;

    return ($participant->getMorphClass() === $type1 && $participant->getKey() == $id1)
        || ($participant->getMorphClass() === $type2 && $participant->getKey() == $id2);
});