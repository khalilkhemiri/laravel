<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Carbon\Carbon;

class UserActivity implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('presence-online-users');
    }

    public function broadcastAs()
    {
        return 'user.activity';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'last_activity' => $this->formatLastActivity($this->user->last_activity),
        ];
    }

    private function formatLastActivity($lastActivity)
    {
        if ($lastActivity instanceof Carbon) {
            return $lastActivity->toIso8601String();
        } elseif (is_string($lastActivity)) {
            return $lastActivity;
        } else {
            return now()->toIso8601String();
        }
    }
}