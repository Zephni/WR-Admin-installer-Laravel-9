<?php

namespace App\Classes\ManageableFields;
use Illuminate\View\ComponentAttributeBag;

class Select extends ManageableField
{
    public function __construct(string $name, string $defaultValue = null, array $options)
    {
        parent::__construct($name, $defaultValue, 'select');
        $this->options([
            'options' => $options, // key => value
        ]);
    }

    public function render()
    {
        return view('components.admin.manageable-fields.select', [
            'label' => $this->getLabel(),
            'name' => $this->name,
            'value' => $this->getValue(),
            'options' => $this->options,
            'attributes' => new ComponentAttributeBag([
                ($this->options['readonly'] == 'true' ? 'readonly' : '') => ''
            ])
        ]);
    }

    public function getOptionValue(): string
    {
        // If the value is not in the options array, return the value
        if (!array_key_exists($this->getValue(), $this->options['options'])) {
            return $this->getValue();
        }
        // Otherwise, return the human readable value
        else
        {
            return $this->options['options'][$this->getValue()];
        }
    }
}
