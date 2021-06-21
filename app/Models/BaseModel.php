<?php

namespace App\Models;

use Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Database\Eloquent\Model;
use App\Components\Aws;

class BaseModel extends Model
{
    private $errors = [];

    public static function getValidationRules()
    {
        if (isset(static::$rules) && count(static::$rules)) {
            return static::$rules;
        }
        return [];
    }

    public function isValid()
    {
        $values = $this->getAttributes();
        $rules = self::getValidationRules();
        $check = \Validator::make($values, $rules);
        $is_valid = !$check->fails();

        $this->errors = $is_valid ? new MessageBag() : $check->messages();
        return $is_valid;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function save(array $options = [])
    {
        if (!$this->isValid()) {
            return false;
        }
        return parent::save($options);
    }

    public function getAwsLink($value)
    {
        if ($value) {
            return (new Aws())->requestSignedUrl($value);
        }
        return null;
    }
}
