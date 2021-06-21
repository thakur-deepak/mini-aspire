<?php

namespace App\Modules\Api\V1\Controllers;

use App\Notifications\PasswordResetRequest;
use App\Repositories\Password\PasswordInterface;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;
use Validator;

class PasswordResetController extends ApiController
{

    private $password;

    private $user;

    private $input;

    public function __construct(UserInterface $user, PasswordInterface $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function create(Request $request)
    {
        $this->input = $request->all();
        $validation_rules = ['email' => 'required|string|email'];
        $this->validateRequestInputs($validation_rules);
        $user = $this->user->findVerifiedUserbyEmail($this->input['email']);

        if (! $user) {
            $this->showBadRequestError(
                ['email' => __('messages.password.not_found')],
                __('messages.user.not_found'),
                400
            );
        }

        $password_reset = $this->password->updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60),
            ]
        );

        if ($password_reset) {
            $user->notify(new PasswordResetRequest($password_reset->token));
        }

        return $this->showSuccessRequest([], __('messages.password.email'), 200);
    }

    public function find($token)
    {
        $password_reset = $this->password->find($token);

        if (! $password_reset) {
            $this->showBadRequestError(
                ['token' => __('messages.user.token_expired')],
                __('messages.error.validation'),
                404
            );
        }

        return $this->showSuccessRequest($password_reset, __('messages.password.valid'), 200);
    }

    public function reset(Request $request)
    {
        $this->input = $request->all();

        $validation_rules = [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|regex:' . config('constants.PASSWORD_REGEX'),
            'token' => 'required|string',
        ];
        $this->validateRequestInputs($validation_rules);

        $password_reset = $this->password->findByEmailandToken($this->input['email'], $this->input['token']);

        if (! $password_reset) {
            $this->showBadRequestError(
                ['token' => __('messages.user.token_expired')],
                __('messages.error.validation'),
                404
            );
        }

        $user = $this->user->findUserbyEmail($this->input['email']);
        if (! $user) {
            $this->showBadRequestError(
                ['token' => __('messages.user.not_found')],
                __('messages.error.validation'),
                404
            );
        }
        $this->user->update($user['id'], ['password' => $this->input['password']]);
        $password_reset->delete();

        return $this->showSuccessRequest(
            ['email' => $this->input['email']],
            __('messages.password.update_success'),
            200
        );
    }

    private function validateRequestInputs($rules)
    {
        $validator = Validator::make($this->input, $rules, __('messages.validation'));
        if ($validator->fails()) {
            $this->showBadRequestError($validator->errors(), __('messages.error.validation'), 400);
        }
        return true;
    }
}
