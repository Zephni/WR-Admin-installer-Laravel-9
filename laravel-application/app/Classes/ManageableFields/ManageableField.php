<?php

namespace App\Classes\ManageableFields;

class ManageableField
{
    public string $name;
    public string $type;
    public string $value;
    public array $options = [
        'placeholder' => '',
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
     * render
     * Override this method to render the field
     * @return mixed
     */
    public function render()
    {
        return '';
    }

    /**
     * options
     *
     * @param  mixed $options
     * @return ManageableField
     */
    public function options(array $options): ManageableField
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    public function getLabel(): string
    {
        return \Str::of($this->name)->replace('_', ' ')->title();
    }

    public function getPlaceholder(): string
    {
        return $this->options['placeholder'] ?? 'Enter ' . \Str::lower(\Str::title($this->name));
    }
}
