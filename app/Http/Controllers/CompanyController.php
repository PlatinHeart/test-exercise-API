<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;

class CompanyController extends Controller
{
    private $companyRepository;

    private $userRepository;

    public function __construct(CompanyRepository $companyRepository, UserRepository $userRepository)
    {
        $this->companyRepository = $companyRepository;

        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanies()
    {
        $user = $this->userRepository->findUserByApiToken($_GET['api_token']);

        if ($user['token_expire_date'] < Carbon::now()) {
            return response()->json(['message' => 'Token is expire, please signIn']);
        }

        return response()->json($this->companyRepository->getCompanies($user));
    }

    /**
     * @param CompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCompany(CompanyRequest $request)
    {
        $user = $this->userRepository->findUserByApiToken($request['api_token']);

        if ($user['token_expire_date'] < Carbon::now()) {
            return response()->json(['message' => 'Token is expire, please signIn']);
        }
        $company = $this->companyRepository->addCompanies($request, $user);

        return response()->json([
            'message' => 'Company successful added',
            'Company' => $company['title'],
            'Phone' => $company['phone'],
            'Description' => $company['description']
        ]);
    }
}
