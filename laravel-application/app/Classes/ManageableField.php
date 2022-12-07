<?php

namespace App\ManageableModel;

class ManageableField
{
    public string $label;
    public string $name;
    public string $type;
    public string $value;
    public array $options;

    public function __construct(string $label, string $name, string $type, string $defaultValue, array $options = [])
    {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->value = $defaultValue;
        $this->options = $options;
    }
}
