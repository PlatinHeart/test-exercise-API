<?php


namespace App\Repositories;

use App\Models\PasswordRecovery;

class PasswordRecoveryRepository
{
    public function query()
    {
        return PasswordRecovery::query();
    }

    public function addRecoveryRecord($email, $recovery_token)
    {
        return $this->query()->create([
            'email' => $email,
            'recovery_token' => $recovery_token,
            'status' => 'new'
        ]);
    }

    public function findRecoveryRecordByEmail($email)
    {
        return $this->query()->where('email', $email)->get();
    }

    public function findRecoveryRecordByToken($recovery_token)
    {
        return $this->query()->where('recovery_token', $recovery_token)->first();
    }

    public function updateRecordStatus($record_id)
    {
        return $this->query()->where('id', $record_id)->update([
            'status' => 'used'
        ]);
    }

    public function changeRecordStatus($record_id, $email)
    {
        return $this->query()->where('email', $email)->where('id', '<>', $record_id)->update([
            'status' => 'Not valid'
        ]);
    }
}
