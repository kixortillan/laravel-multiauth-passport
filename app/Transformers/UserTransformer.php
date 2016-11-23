<?php

namespace App\Transformer;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    public function transform(User $user)
    {
        return [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ];
    }

}
