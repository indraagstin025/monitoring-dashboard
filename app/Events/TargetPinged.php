<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TargetPinged implements \Illuminate\Contracts\Broadcasting\ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $target;

    /**
     * Create a new event instance.
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    public function broadcastWith(): array
    {
        $targets = $this->target->user
            ->targets()
            ->get(['id', 'status', 'is_paused']);

        return [
            'id' => $this->target->id,
            'status' => $this->target->status,
            'last_response_time' => $this->target->last_response_time,
            'consecutive_failures' => $this->target->consecutive_failures,
            'last_checked_at' => $this->target->last_checked_at ? $this->target->last_checked_at->diffForHumans() : 'Baru saja',
            'uptime_1d' => $this->target->uptime_1d,
            'uptime_7d' => $this->target->uptime_7d,
            'uptime_30d' => $this->target->uptime_30d,
            'summary' => [
                'total' => $targets->count(),
                'up' => $targets->where('status', 'up')->count(),
                'down' => $targets->where('status', 'down')->count(),
                'unstable' => $targets->whereIn('status', ['unstable', 'unknown'])->count(),
                'paused' => $targets->where('is_paused', true)->count(),
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'TargetPinged';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->target->user_id),
        ];
    }
}
