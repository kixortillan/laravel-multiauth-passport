<?php

namespace App\Repositories;

use App\User;
use App\Models\Group;

class UserRepository
{

    /**
     * 
     * @param string $email
     * @return \App\User
     */
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * 
     * @param array $data
     * @return \App\User
     */
    public function createUser(array $data)
    {
        $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => isset($data['password']) ? bcrypt($data['password']) : bcrypt(strtotime('now') . uniqid()),
                    'verify_token' => isset($data['password']) ? $data['verify_token'] : null,
        ]);

        $defaultGroup = Group::where('name', 'employee')->first();

        $user->group()->save($defaultGroup);

        return $user;
    }

    /**
     * 
     * @param string $token
     * @return \App\User
     */
    public function findByVerificationToken($token)
    {
        return User::where('verify_token', $token)->first();
    }

}
