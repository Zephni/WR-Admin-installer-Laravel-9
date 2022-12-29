<?php

namespace App\Classes\ManageableFields;

class ManageableField
{
    public string $name;
    public string $type;
    public string $value;

    /**
     * __construct
     *
     * @param  mixed $label
     * @param  mixed $name
     * @param  mixed $type
     * @param  mixed $defaultValue
     * @return void
     */
    public function __construct(string $name, mixed $defaultValue = null, string $type = 'text')
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = $defaultValue ?? '';
    }
}
