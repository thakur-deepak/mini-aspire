<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $attributes): User
    {
        $user = $this->user->create($attributes);
        $token = $user->createToken(env('TOKEN_NAME', 'API TOKEN'));
        $user['token'] = $token->plainTextToken;
        return $user;
    }

    public function findBy($column_name, $value)
    {
        return $this->user->where($column_name, $value)->first();
    }

    public function update(int $id, array $attributes): bool
    {
        if (! empty($attributes['password'])) {
            $attributes['password'] = \bcrypt($attributes['password']);
        }
        return (bool) $this->user->whereId($id)->update($attributes);
    }

    public function find($id)
    {
        return $this->user->whereId($id)->first();
    }
}
