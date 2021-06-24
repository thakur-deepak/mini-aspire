<?php

namespace App\Modules\Api\V1\Controllers;

use App\Components\AccessTokenHandler;
use Illuminate\Http\Request;
use App\Repositories\User\UserInterface;
use App\Repositories\AccessToken\AccessTokenInterface;
use App\Http\Requests\Authentication;
use DateTime;
use DateTimeZone;
use DateInterval;

class AuthenticationController extends ApiController
{
    public function __construct(UserInterface $user, AccessTokenInterface $access_token)
    {
        $this->user = $user;
        $this->access_token = $access_token;
    }

    public function login(Authentication $request)
    {
        $this->input = $request->validated();
        $this->input['email'] = strtolower($this->input['email']);
        $user = $this->user->findBy('email', $this->input['email']);
        if ($user && \Hash::check($this->input['password'], $user->password)) {
            if (empty($user->email_verified_at)) {
                return $this->sendErrorResponse([], trans('messages.not_verified'), 401);
            }
            $access_token = (new AccessTokenHandler($request))->get();
            $access_token['user_id'] = $user->id;

            $remember_me = $request->get('remember_me', false);
            $lifetime = $remember_me ? config('session.remember_me_lifetime') : config('session.lifetime');

            $now = new DateTime('now', new DateTimeZone('UTC'));
            $access_token['expires_at'] = $now->add(new DateInterval("PT{$lifetime}M"))->format('Y-m-d H:i:s');
            $access_token['is_remembered'] = $remember_me;

            $this->access_token->create($access_token);
            $user_data = $this->user->findWithRole($user);
            $user_data['access_token'] = $access_token['token'];
            return $this->showSuccessResponse($user_data, trans('messages.loggedin'));
        }
        return $this->sendErrorResponse([], trans('messages.incorrect_credentials'), 401);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if (! empty($token)) {
            $access_token = new AccessTokenHandler($request);
            $deleted = $access_token->delete();
            if (!$deleted) {
                return $this->sendErrorResponse([], trans('messages.invalid_access_token'), 403);
            }
            return $this->showSuccessResponse([], trans('messages.loggedout'));
        }
        return $this->sendErrorResponse([], trans('messages.token_not_found'), 401);
    }

    public function deleteExpiredSessions()
    {
        $this->access_token->deleteExpiredAccessTokens();
        return $this->showSuccessResponse([], 'success');
    }

    private function wrongAttempt($request)
    {
        $wrong_login_info = [
            'email' => $request->email,
            'ip' => $request->ip(),
            'created_at' => date('Y-m-d H:i:s'),
            'user_agent' => $request->server('HTTP_USER_AGENT')
        ];
        $data = Cache::has('login_attempt_' . $request->ip()) ? Cache::get('login_attempt_' . $request->ip()) : [];
        $data[] = $wrong_login_info;
        Cache::put('login_attempt_' . $request->ip(), $data, config('constants.LOGIN.MAX_WRONG_ATTEMPT_CACHE_TIME'));
        return Cache::get('login_attempt_' . $request->ip());
    }

    private function hasExceedLoginLimit($request)
    {
        if (Cache::has('freeze_login_' . $request->ip())) {
            return 1;
        }
        if (
            Cache::has('login_attempt_' . $request->ip())
            && count(Cache::get('login_attempt_' . $request->ip())) >= config('constants.LOGIN.WRONG_ATTEMPT_LIMIT')
        ) {
            $this->freezeLogin($request);
            return 1;
        }
        return 0;
    }

    private function freezeLogin($request)
    {
        $this->flushLoginAttempts($request);
        $data = [
            'ip' => $request->ip(),
            'created_at' => date('Y-m-d H:i:s'),
            'user_agent' => $request->server('HTTP_USER_AGENT')
        ];
        return Cache::put('freeze_login_' . $request->ip(), $data, config('constants.LOGIN.MAX_FREEZE_TIME'));
    }

    private function flushLoginAttempts($request)
    {
        return Cache::forget('login_attempt_' . $request->ip());
    }
}
