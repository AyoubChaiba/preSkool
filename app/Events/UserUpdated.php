<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserUpdated
{
    use Dispatchable, SerializesModels;

    public $user;
    public $password;
    public $role;

    /**
     * Create a new event instance.
     *
     * @param  User  $user
     * @param  string  $password
     * @return void
     */
    public function __construct(User $user, $password, $role)
    {
        $this->user = $user;
        $this->password = $password;
        $this->role = $role;
    }
}
