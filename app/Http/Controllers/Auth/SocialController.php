<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Social\SaveProviderData;
use App\Http\Controllers\Auth\Helpers\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

/**
 * @tags Auth
 */
class SocialController extends Controller
{
    use AuthenticatesUsers, SaveProviderData;

    private array $network = [
        'google' => 'google',
    ];
    private array $networkChecker;

    private string $serviceNotFound = 'Mạng xã hội "%s" không có sẵn.';
    private string $serviceNotEnabled = 'Mạng xã hội "%s" không được kích hoạt.';
    private string $serviceError = "Lỗi không xác định. Dịch vụ không hoạt động.";

    public function __construct()
    {
        Log::debug('GOOGLE_CLIENT_ID: ' . env('GOOGLE_CLIENT_ID'));
        Log::debug('GOOGLE_CLIENT_SECRET: ' . env('GOOGLE_CLIENT_SECRET'));

        $this->networkChecker = [
            'google' => (
                env('GOOGLE_CLIENT_ID')
                && env('GOOGLE_CLIENT_SECRET')
            ),
        ];
    }
    /**
     * Đăng nhập bằng Google.
     * @param string $provider `google`
     * @unauthenticated
     */

    public function getProviderTargetUrl(string $provider = "google")
    {

        $serviceKey = $this->network[$provider] ?? null;
        if (empty($serviceKey)) {
            $message = sprintf($this->serviceNotFound, $provider);
            return errors($message);
        }

        // Check if the Provider is enabled
        // $providerIsEnabled = (array_key_exists($provider, $this->networkChecker) && $this->networkChecker[$provider]);
        // if (!$providerIsEnabled) {
        //     $message = sprintf($this->serviceNotEnabled, $provider);
        //     return errors($message);
        // }

        // Redirect to the Provider's website
        try {
            $socialiteObj = Socialite::driver($serviceKey)->stateless();
            return success('Get link thành công', ['url' => $socialiteObj->redirect()->getTargetUrl()]);
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = $this->serviceError;
            }
            return errors($message);
        }
    }

    /**
     * Callback google
     *
     * @param string $provider `google`
     * @param string $code Authorization code. Example: 4/0AdLIrYeeHKx5YRnNmGPt9SrrXuOKVOPw9mH47TLrXMwA0SHYpGv3Tyc7h01_Eb_8syaT7A
     * @param string $scope Scope of the authorization. Example: email profile openid https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email
     * @param int $authuser Authenticated user ID. Example: 0
     * @param string $prompt Prompt type. Example: consent
     * @param \Illuminate\Http\Request $request HTTP request object
     * @unauthenticated
     */
    public function handleProviderCallback(Request $request, $provider = "google")
    {

        $serviceKey = $this->network[$provider] ?? null;
        if (empty($serviceKey)) {
            $message = sprintf($this->serviceNotFound, $provider);
            return errors($message);
        }

        try {
            // Lấy mã ủy quyền từ yêu cầu
            $authCode = request()->input('code');
            if (empty($authCode)) {
                return errors('Authorization code không hợp lệ');
            }

            // Đổi mã ủy quyền lấy access token và thông tin người dùng
            $socialiteUser = Socialite::driver($serviceKey)->stateless()->user();

            if (!$socialiteUser) {
                return errors('Lỗi không xác định vui lòng thử lại');
            }

            // Email not found
            if (!filter_var($socialiteUser->getEmail(), FILTER_VALIDATE_EMAIL)) {
                return errors('Email không tồn tại');
                // return response()->json(['success' => false, 'message' => 'Email không tồn tại', 'provider' => str($provider)->headline()]);
            }
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = $this->serviceError;
            }
            return errors($message);
        }

        // SAVE USER
        return $this->saveUser($provider, $socialiteUser);
    }
}
