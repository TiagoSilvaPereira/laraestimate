<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser {

    public function execute(array $data) : User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

}