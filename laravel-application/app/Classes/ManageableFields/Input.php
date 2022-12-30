<?php

namespace App\Classes\ManageableFields;
use Illuminate\View\ComponentAttributeBag;

class Input extends ManageableField
{
    public function __construct(string $name, string $defaultValue = null, string $type = 'text')
    {
        parent::__construct($name, $defaultValue, $type);
    }

    public function render()
    {
        return view('components.admin.manageable-fields.input', [
            'label' => $this->getLabel(),
            'name' => $this->name,
            'value' => $this->getValue(),
            'type' => $this->type,
            'options' => $this->options,
            'attributes' => new ComponentAttributeBag([
                'placeholder' => $this->getPlaceholder(),
                ($this->options['readonly'] == 'true' ? 'readonly' : '') => ''
            ])
        ]);
    }
}
