<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecoveryPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SetNewPasswordRequest;
use App\Http\Requests\SigInRequest;
use App\Mail\RecoveryPasswordMail;
use App\Repositories\PasswordRecoveryRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class UserController extends Controller
{
    private $userRepository;

    private $passwordResetRepository;

    public function __construct(UserRepository $userRepository, PasswordRecoveryRepository $passwordResetRepository)
    {
        $this->userRepository = $userRepository;
        $this->passwordResetRepository = $passwordResetRepository;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $request = $request->validated();

        $user = $this->userRepository->createUser($request);

        return response()->json([
            'message' => 'You\'re have successfully registered',
            'user' => [$user['first_name'], $user['last_name'], $user['email']]
        ], 200);
    }

    /**
     * @param SigInRequest $request
     * @return JsonResponse
     */
    public function signIn(SigInRequest $request)
    {
        $request = $request->validated();

        $user = $this->userRepository->findUserByEmail($request['email']);

        if (!$user or (!Hash::check($request['password'], $user['password']))) {
            return response()->json(['message' => 'Email or password incorrect, please try again'], 400);
        }
        $api_token = Str::random(60);

        $token_expire_date = Carbon::now()->addDay();

        $this->userRepository->addApiToken($api_token, $user['email']);

        return response()->json(['message' => 'You\'re successful SignIn', 'api_token' => $api_token, 'token_expire_date' => $token_expire_date], 200);
    }

    /**
     * @param RecoveryPasswordRequest $request
     * @return JsonResponse
     */
    public function recoveryPassword(RecoveryPasswordRequest $request)
    {
        $request = $request->validated();

        $email = $request['email'];

        $user = $this->userRepository->findUserByEmail($email);

        if (!$user) {
            return response()->json(['message' => 'User with this email not found'], 400);
        }

        $recover_token = Str::random(60);

        Mail::to($email)->send(new RecoveryPasswordMail($recover_token));

        $recovery_record = $this->passwordResetRepository->addRecoveryRecord($email, $recover_token);

        $existing_records = $this->passwordResetRepository->findRecoveryRecordByEmail($request['email']);

        if (count($existing_records) > 1) {
            $this->passwordResetRepository->changeRecordStatus($recovery_record['id'], $email);
        }

        return response()->json(['message' => 'A password recovery token has been sent to your email'], 200);
    }

    /**
     * @param SetNewPasswordRequest $request
     * @return JsonResponse
     */
    public function setNewPassword(SetNewPasswordRequest $request)
    {
        $request = $request->validated();

        $recovery_record = $this->passwordResetRepository->findRecoveryRecordByToken($request['recovery_token']);

        $user = $this->userRepository->findUserByEmail($request['email']);

        if ($user) {
            if ($recovery_record['status'] == 'new' AND $recovery_record['recovery_token'] == $request['recovery_token']) {

                $this->userRepository->updatePassword($user->id, $request['password']);

                $this->passwordResetRepository->updateRecordStatus($recovery_record['id']);

                return response()->json(['message' => 'You\'re successful change password'], 200);
            }
        }

        return response()->json(['message' => 'User with this email not found, or token not valid'], 400);
    }
}
