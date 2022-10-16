<?php


namespace App\Repositories;


use App\Models\Company;

class CompanyRepository
{
    public function query()
    {
        return Company::query();
    }

    public function getCompanies($user)
    {
        return $this->query()->where('user_id', $user->id)->get();
    }

    public function addCompanies($request, $user)
    {
        return $this->query()->create([
            'title' => $request->title,
            'phone' => $request->phone,
            'description' => $request->description,
            'user_id' => $user->id,
        ]);
    }
}
