<?php

namespace App\Classes\ManageableFields;

class ManageableField
{
    public string $name;
    public string $type;
    public string $value;
    public array $options = [
        'hide' => false,
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
     * Do not override this method, it is used by the views to check if the field is renderable first
     * @return mixed
     */
    public function renderCheck()
    {
        if (isset($this->options['hide']) && $this->options['hide'] === true) {
            return '';
        }

        return $this->render();
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
}
