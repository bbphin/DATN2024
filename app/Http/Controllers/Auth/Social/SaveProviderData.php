<?php

namespace App\Http\Controllers\Auth\Social;

use App\Helpers\Ip;
use App\Http\Resources\UserResource;
use App\Models\Blacklist;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use App\Notifications\SendPassword;
use App\Notifications\SendPasswordAndVerificationInfo;
use App\Notifications\UserNotification;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

trait SaveProviderData
{
    private string $userNotSavedError = "Lỗi không xác định. Dữ liệu người dùng lưu thất bại.";

    /**
     * @param string $provider
     * @param SocialiteUser $providerData
     * @return \Illuminate\Http\JsonResponse
     */
    protected function saveUser(string $provider, SocialiteUser $providerData)
    {

        try {
            $remoteId = $providerData->getId() ?? NULL;
            $name = $this->getName($providerData);
            $email = $providerData->getEmail();
            $avatar = $providerData->getAvatar();

            // GET LOCAL USER
            $user = User::query()
                ->where('email', $email)
                ->where('token_google', $remoteId)
                ->first();

            // CREATE LOCAL USER IF DON'T EXISTS
            if (empty($user)) {
                // Before... Check if user has not signed up with an email
                $user = User::query()
                    ->where('email', $email)
                    ->first();

                if (empty($user)) {
                    // Generate random password
                    $randomPassword = getRandomPassword(8);

                    // Register the User (As New User)
                    $userInfo = [
                        'name'              => $name,
                        'email'             => $email,
                        'password'          => Hash::make($randomPassword),
                        'created_at'        => now()->format('Y-m-d H:i:s'),
                        'role' => 0,
                    ];
                    $user = new User($userInfo);
                    $user->save();

                    // Send Generated Password by Email
                    try {
                        $user->notify(new SendPassword($user, $randomPassword));
                    } catch (\Throwable $e) {
                    }
                } else {
                    // Update 'created_at' if empty (for time ago module)
                    if (empty($user->created_at)) {
                        $user->created_at = now()->format('Y-m-d H:i:s');
                    }
                    $user->save();
                }
            } else {
                // Update 'created_at' if empty (for time ago module)
                if (empty($user->created_at)) {
                    $user->created_at = now()->format('Y-m-d H:i:s');
                }
                $user->save();
            }
            return $this->loginUser($user, $provider);
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = $this->userNotSavedError ?? '';
            }

            return response()->json(['success'=>false,'message'=>$message]);
        }
    }

    /**
     * @param \App\Models\User $user
     * @param string|null $deviceName
     * @return \Illuminate\Http\JsonResponse
     */
    protected function loginUser(User $user, ?string $deviceName = null)
    {
        $user->tokens()->delete();
        if (auth()->loginUsingId($user->id)) {
            // Create the API access token
            $deviceName = $user->email;
            $token = $user->createToken($deviceName);

            $data = [
                'success' => true,
                'result'  => new UserResource($user),
                'extra'   => [
                    'authToken' => $token->plainTextToken,
                    'tokenType' => 'Bearer',
                ],
            ];

            // If the user has not yet specified the type of account, redirect him to his user area where he can do so.
            $data['extra']['userTypeId'] = $user->id ?? null;

            return response()->json($data);
        } else {
            return response()->json(['success'=> false,'message' => 'Không thể đăng nhập tài khoản này.']);
        }
    }

    /**
     * @param \Laravel\Socialite\Contracts\User $providerData
     * @return string|null
     */
    private function getName(SocialiteUser $providerData): ?string
    {
        $name = $providerData->getName();
        if ($name != '') {
            return $name;
        }

        // Get the user's name (First Name & Last Name)
        $name = (isset($providerData->name) && is_string($providerData->name)) ? $providerData->name : '';
        if ($name == '') {
            // facebook
            if (isset($providerData->user['first_name']) && isset($providerData->user['last_name'])) {
                $name = $providerData->user['first_name'] . ' ' . $providerData->user['last_name'];
            }
        }
        if ($name == '') {
            // linkedin
            $name = (isset($providerData->user['formattedName'])) ? $providerData->user['formattedName'] : '';
            if ($name == '') {
                if (isset($providerData->user['firstName']) && isset($providerData->user['lastName'])) {
                    $name = $providerData->user['firstName'] . ' ' . $providerData->user['lastName'];
                }
            }
        }

        return is_string($name) ? $name : 'Không xác định';
    }
}
