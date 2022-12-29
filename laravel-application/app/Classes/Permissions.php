<?php

namespace App\Classes;

class Permissions
{
    public bool $master = false;
    public bool $admin = false;

    /**
     * __construct
     *
     * @param  mixed $permissions
     * @return void
     */
    public function __construct(array $permissions = [])
    {
        foreach($permissions as $key => $value)
        {
            $this->$key = $value;
        }
    }

    /**
     * asString
     * Returns a serialized version of the permissions object
     * @return string
     */
    public function asString(): string
    {
        return serialize($this);
    }

    /**
     * fromString
     * Returns a permissions object from a serialized string
     * @param  mixed $string
     * @return Permissions
     */
    public static function fromString(string $string): Permissions
    {
        return unserialize($string);
    }
}
