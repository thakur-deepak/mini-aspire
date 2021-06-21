<?php

namespace App\Models;

class User extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profession', 'email', 'password', 'remember_me'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_code'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static $rules = [
        'email' => 'required|unique:users|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (count($attributes)) {
            $this->setDataInternally($attributes);
        }
    }

    public function setDataInternally($attributes = [])
    {
        if (isset($attributes['email'])) {
            $this->email = strtolower(trim($attributes['email']));
        }

        if (isset($attributes['password']) && !empty($attributes['password'])) {
            $this->password = \bcrypt($attributes['password']);
        }
        $this->verification_code = str_random(30);
        return true;
    }

    public function professional()
    {
        return $this->hasOne('App\Models\Professional');
    }

    public function provider()
    {
        return $this->hasOne('App\Models\Provider');
    }
}
