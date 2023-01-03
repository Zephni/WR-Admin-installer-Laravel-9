<?php

namespace App\Classes\ManageableFields;

class ManageableField
{
    public string $name;
    public string $type;
    public string $value;
    public array $options = [
        'readonly' => false,
        'placeholder' => '',
        'info' => false,
    ];

    /**
     * __construct
     *
     * @param  mixed $name
     * @param  mixed $defaultValue
     * @param  mixed $type
     * @return void
     */
    public function __construct(string $name, mixed $defaultValue = null, string $type = 'text')
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = $defaultValue ?? '';
    }

    /**
     * Override this method to render the field
     * @return mixed
     */
    public function render()
    {
        return '';
    }

    /**
     * Appends the given options to the options array
     * @param  mixed $options
     * @return ManageableField
     */
    public function options(array $options): ManageableField
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    /**
     * Return old value if it exists, otherwise return the default value
     * @return string
     */
    public function getValue(): string
    {
        return old($this->name) ?? $this->value;
    }

    /**
     * Prettifies the label by replacing underscores with spaces and capitalizing the first letter of each word
     * @return string
     */
    public function getLabel(): string
    {
        return \Str::of($this->name)->replace('_', ' ')->title()->trim();
    }

    /**
     * Return the placeholder if it exists, otherwise return a default placeholder
     * @return string
     */
    public function getPlaceholder(): string
    {
        return !empty($this->options['placeholder'])
            ? $this->options['placeholder']
            : 'Enter ' . \Str::of($this->name)->replace('_', ' ')->lower()->trim();
    }
}
