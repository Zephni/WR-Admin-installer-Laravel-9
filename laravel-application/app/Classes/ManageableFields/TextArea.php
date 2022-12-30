<?php

namespace App\Classes\ManageableFields;
use Illuminate\View\ComponentAttributeBag;

class TextArea extends ManageableField
{
    public function __construct(string $name, string $defaultValue = null, int $rows = 5)
    {
        parent::__construct($name, $defaultValue, 'textarea');
        $this->options([
            'rows' => $rows
        ]);
    }

    public function render()
    {
        return view('components.admin.manageable-fields.textarea', [
            'label' => $this->getLabel(),
            'name' => $this->name,
            'value' => $this->getValue(),
            'options' => $this->options,
            'attributes' => new ComponentAttributeBag([
                'placeholder' => $this->getPlaceholder(),
                'rows' => $this->options['rows'],
                ($this->options['readonly'] == 'true' ? 'readonly' : '') => ''
            ])
        ]);
    }
}
