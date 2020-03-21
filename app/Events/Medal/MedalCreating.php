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
        $directory = request()->user()
            ? request()->user()->id
            : 'guest';

        $path = request()
            ->file('image')
            ->store('medals/' . $directory, 'public');

        $medal->path = $path;
    }
}
