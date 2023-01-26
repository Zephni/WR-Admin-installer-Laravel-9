<?php

namespace App\Classes;

class JSONFormattedField
{
    /**
     * __construct
     *
     * @param  mixed $data
     * @return void
     */
    public function __construct(array $data = [])
    {
        foreach($data as $key => $value)
        {
            $this->$key = $value;
        }
    }

    /**
     * Returns a serialized version of the data object
     * @return string
     */
    public function asString(): string
    {
        // Get object vars
        $vars = get_object_vars($this);

        // Create an array of the vars and their values
        $data = [];
        foreach($vars as $key => $value)
        {
            $data[$key] = $value;
        }

        // Return as JSON
        return json_encode($data);
    }

    /**
     * Returns a data object from a serialized string
     * @param  mixed $string
     * @return JSONFormattedField
     */
    public static function fromString(mixed $string): JSONFormattedField
    {
        // If the string is empty or null, return an empty object. This may happen in case where field is not yet set in the database
        if(empty($string))
        {
            return new static();
        }

        // Create a new object of type $this, in other words the class that called this method (which could be inheriting this one)
        $class = get_called_class();
        $jsonFormattedField = new $class();

        // Decode JSON
        $array = json_decode($string, true);

        // Set the object vars
        foreach($array as $key => $value)
        {
            $jsonFormattedField->$key = $value;
        }

        // Return the permissions object
        return $jsonFormattedField;
    }

    /**
     * __toString magic method
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->asString();
    }

    /**
     * asArray
     * Returns the object as an array
     *
     * @return array
     */
    public function asArray(): array
    {
        // Get object vars
        $vars = get_object_vars($this);

        // Create an array of the vars and their values
        $data = [];
        foreach($vars as $key => $value)
        {
            $data[$key] = $value;
        }

        // Return as array
        return $data;
    }

    /**
     * get
     * Can take any number of string arguments, will assume we are getting first property or a nested array
     *
     * @param string[] $attributes
     * @return mixed
     */
    public function get(... $attributes): mixed
    {
        // If $attributes[0] is an array, use that instead
        if(is_array($attributes[0]))
        {
            $attributes = $attributes[0];
        }

        // Check atleast one attribute was passed and that the first is a valid property
        if(count($attributes) == 0 || !property_exists($this, $attributes[0]))
        {
            return false;
        }

        // Get the first attribute property
        $attribute = $this->{$attributes[0]};

        // If this is the last attribute, return it
        if(count($attributes) == 1)
        {
            return $attribute;
        }

        // Loop through the following attributes to get the nested attribute, each attribute is a key
        for($i = 1; $i < count($attributes); $i++)
        {
            // If attribute does not exist, return false
            if(!isset($attribute[$attributes[$i]]))
            {
                return false;
            }

            // Get the attribute
            $attribute = $attribute[$attributes[$i]];
        }

        // Return the attribute
        return $attribute;
    }
}
