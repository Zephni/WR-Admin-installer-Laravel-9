<?php

namespace App\Classes\ManageableFields;
use Illuminate\View\ComponentAttributeBag;

class Select extends ManageableField
{
    public function __construct(string $name, string $defaultValue = null, array $options)
    {
        parent::__construct($name, $defaultValue, 'select');
        $this->mergeData([
            'options' => $options, // key => value
        ]);
    }

    public function render()
    {
        return view('components.admin.manageable-fields.select', [
            'label' => $this->getLabel(),
            'name' => $this->name,
            'value' => $this->getValue(),
            'data' => $this->getAllData(),
            'attributes' => new ComponentAttributeBag([
                ($this->getData('readonly') == true ? 'readonly' : '') => ''
            ])
        ]);
    }

    public function getBrowseValue(): string
    {
        // If options is not an array, return the value
        if (!is_array($this->getData('options'))) {
            return $this->getValue();
        }

        $fieldOptions = (array)$this->getData('options');

        // If the value is not in the options array, return the value
        if (!array_key_exists($this->getValue(), $fieldOptions)) {
            return $this->getValue();
        }
        // Otherwise, return the human readable value
        else
        {
            return $fieldOptions[$this->getValue()];
        }
    }
}
