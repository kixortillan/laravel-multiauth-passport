<?php

namespace App\Repositories;

use App\User;
use App\Models\Group;

class UserRepository
{

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * 
     * @param array $data
     * @return type
     */
    public function createUser(array $data)
    {
        $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => isset($data['password']) ? bcrypt($data['password']) : bcrypt(strtotime('now') . uniqid()),
        ]);

        $defaultGroup = Group::where('name', 'employee')->first();

        $user->group()->save($defaultGroup);

        return $user;
    }

}
