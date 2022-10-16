<?php


namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function query()
    {
        return User::query();
    }

    public function createUser($request)
    {
        return $this->query()->create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'phone' => $request['phone']
        ]);
    }

    public function findUserByEmail($user_email)
    {
        return $this->query()->where('email', $user_email)->first();
    }

    public function findUserByApiToken($api_token)
    {
        return $this->query()->where('api_token', $api_token)->first();
    }

    public function addApiToken($api_token, $user_email)
    {
        return $this->query()->where('email', $user_email)->update([
            'api_token' => $api_token,
            'token_expire_date' => Carbon::now()->addDay()
        ]);
    }

    public function updatePassword($user_id, $password)
    {
        return $this->query()->where('id', $user_id)->update([
            'password' => Hash::make($password)
        ]);
    }
}
