<?php

namespace App\Classes\ManageableFields;

class ManageableField
{
    public string $name;
    public string $type;
    public string $value;
    private array $data = [
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
     * Create an instance using this constructor's parameters
     * Can be used on any extending class
     * @param  mixed ...$parameters
     */
    public static function Create(...$parameters): static
    {
        return new static(...$parameters);
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
     * Merges the given data to the data array
     * @param  mixed $data
     * @return ManageableField
     */
    public function mergeData(array $data): ManageableField
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * Merge the given data if the condition is true
     * @param  bool $condition
     * @param  mixed $data
     * @return ManageableField
     */
    public function mergeDataIf(bool $condition, array $data): ManageableField
    {
        if ($condition) {
            $this->data = array_merge($this->data, $data);
        }

        return $this;
    }

    /**
     * Ges data by key
     * @param  string $key
     * @return mixed if key exists
     * @return null if key does not exist
     */
    public function getData(string $key): mixed
    {
        if (!array_key_exists($key, $this->data)) {
            return null;
        }

        return $this->data[$key];
    }

    /**
     * Gets all data
     * @return array
     */
    public function getAllData(): array
    {
        return $this->data;
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
     * Return the value to be displayed in the browse view, this is the same as getValue() by default
     * but may be overriden, for example, to display a formatted date, or a boolean as a string, or in
     * the case of a "select" field the value of the selected option instead of the key itself
     *
     * @return string
     */
    public function getBrowseValue(): string
    {
        return $this->getValue();
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
        return !empty($this->data['placeholder'])
            ? $this->data['placeholder']
            : 'Enter ' . \Str::of($this->name)->replace('_', ' ')->lower()->trim();
    }
}
