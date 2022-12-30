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
        // Get object vars
        $vars = get_object_vars($this);

        // Create an array of the vars and their values
        $array = [];
        foreach($vars as $key => $value)
        {
            $array[$key] = $value;
        }

        // Return as JSON
        return json_encode($array);
    }

    /**
     * fromString
     * Returns a permissions object from a serialized string
     * @param  mixed $string
     * @return Permissions
     */
    public static function fromString(string $string): Permissions
    {
        // Decode JSON
        $array = json_decode($string, true);

        // Create a new permissions object
        $permissions = new Permissions();

        // Set the object vars
        foreach($array as $key => $value)
        {
            $permissions->$key = $value;
        }

        // Return the permissions object
        return $permissions;
    }
}
