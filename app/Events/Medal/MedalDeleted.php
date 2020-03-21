<?php

namespace App\Events\Medal;

use App\Models\Medal;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MedalDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param $medal Medal
     * @return void
     */
    public function __construct(Medal $medal)
    {
        $this->image($medal);
    }

    /**
     * Remove a imagem da medalha do disco.
     *
     * @param $medal Medal
     * @return void
     */
    private function image($medal)
    {
        // Remove o arquivo da medalha do storage.
        Storage::disk('public')->delete($medal->path);
    }
}
