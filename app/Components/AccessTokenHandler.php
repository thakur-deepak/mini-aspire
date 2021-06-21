<?php

namespace App\Components;

use App\Models\User;
use App\Repositories\User\UserRepository;
use Config;
use Illuminate\Http\Request;

class AccessTokenHandler
{

    private $request;

    private $token;

    private $tokens;

    private $access_token;

    public function __construct(Request $request, $tokens = null)
    {
        $this->request = $request;
        $this->access_token = $request->header('access-token');
        $this->tokens = is_null($tokens) ? [] : json_decode($tokens, true);
    }

    public function get()
    {
        if (! $this->accessTokenExists()) {
            $this->create();
        }
        return $this->jsonEncodedTokens();
    }

    public function getToken()
    {
        return $this->access_token ?? $this->token['token'];
    }

    public function delete()
    {
        if ($this->accessTokenExists()) {
             unset($this->tokens[$this->getTokenKey()]);
        }
        return $this->jsonEncodedTokens();
    }

    private function create()
    {
        $this->token = $this->getRequestMetaData();
        $this->token['token'] = $this->generateAccessToken();
        $this->update();
    }

    private function generateAccessToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    private function getRequestMetaData()
    {
        return ['ip' => $this->request->ip(),
            'device' => '',
            'time' => date(Config::get('constants.DEFAULT_DATETIME_FORMAT')),
            'user_agent' => $this->request->server('HTTP_USER_AGENT'),
        ];
    }

    private function update()
    {
        $this->tokens[] = $this->token;
    }

    private function accessTokenExists()
    {
        if (empty($this->tokens) || empty($this->access_token)) {
            return false;
        }

        return $this->getTokenKey() === false ? false : true;
    }

    private function getTokenKey()
    {
        foreach ($this->tokens as $token_key => $token) {
            if ($this->access_token == $token['token']) {
                $this->token = $token;
                return $token_key;
            }
        }
        return false;
    }


    public function validate()
    {
        if (empty($this->access_token)) {
            return false;
        }

        $model = new UserRepository(new User());
        $user  = $model->findByAccessToken($this->access_token);
        if ($this->validateUser($user) || $this->isAdmin($user)) {
            $this->request->user = $user;
            return true;
        }

        return false;
    }

    public function getRequest()
    {
        return $this->request;
    }

    private function validateUser($user)
    {
        $user_id = $this->request->user_id ?? $this->request->route('id');
        return $user && $user->id == $user_id;
    }

    private function isAdmin($user)
    {
        return $user && $user->role_id == Config::get('constants.USER_ROLE.ADMIN');
    }

    private function jsonEncodedTokens()
    {
        $this->tokens = array_values($this->tokens);
        return json_encode($this->tokens);
    }
}
