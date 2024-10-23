<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\User;
use Carbon\Carbon;

class OnlineUsers extends Component
{
    public $users;

    public function __construct()
    {
        $this->users = User::where('last_activity', '>=', Carbon::now()->subMinutes(5))->get();
    }

    public function render()
    {
        return view('components.online-users');
    }
}