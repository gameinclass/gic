<?php

namespace App\Events\Medal;

use App\Models\Medal;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MedalCreating
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
     * Salva a imagem da medalha no disco.
     *
     * @param $medal Medal
     * @return void
     */
    private function image($medal)
    {
        $path = request()
            ->file('image')
            ->store('medals', 'public');

        $medal->path = $path;
    }
}
