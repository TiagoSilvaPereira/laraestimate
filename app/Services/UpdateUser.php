<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUser {

    public function execute(User $user, array $data) : User
    {
        $data = $this->treatUserData($data);

        $user->update($data);

        return $user->fresh();
    }

    protected function treatUserData(array $data) : array
    {
        if(empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $data;
    }

}