<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PeminjamanStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly ?int $peminjamanId,
        public readonly string $judul,
        public readonly string $pesan,
        public readonly string $status,
        public readonly string $url
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'peminjaman_id' => $this->peminjamanId,
            'judul' => $this->judul,
            'pesan' => $this->pesan,
            'status' => $this->status,
            'url' => $this->url,
        ];
    }
}
