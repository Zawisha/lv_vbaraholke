<?php

namespace App\Broadcasting;

use App\User;

class MessagesChannel
{

    public function join(User $user)
    {
        return true;
    }
}
