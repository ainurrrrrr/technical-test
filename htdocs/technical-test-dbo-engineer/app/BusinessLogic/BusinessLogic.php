<?php

namespace App\BusinessLogic;

class BusinessLogic
{
    protected $scopes;

    protected function toObject($array)
    {
        return json_decode(json_encode($array));
    }

    public function run()
    {
        return null;
    }

    public function getScopes()
    {
        return $this->scopes;
    }

    public function getScope($key)
    {
        return $this->scopes->{$key} ?? null;
    }

    public function hasScope($key): bool
    {
        return isset($this->scopes->{$key});
    }

    public function putScope($key, $value)
    {
        $this->scopes->{$key} = $value;
    }

    public function removeScope($key)
    {
        unset($this->scopes->{$key});
    }
}
