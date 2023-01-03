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
}
