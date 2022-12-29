<?php

namespace App\Traits;

use Str;

/**
 * ManageableModel
 *
 * Trait for models that can be managed in the admin panel
 * @package App\Traits
 */
trait ManageableModel
{
    public function isViewable(): bool
    {
        return false;
    }

    public function isCreatable(): bool
    {
        return false;
    }

    public function isEditable(): bool
    {
        return false;
    }

    public function isDeletable(): bool
    {
        return false;
    }

    public function browseActions(): array
    {
        $actions = [];

        if($this->isEditable()) {
            $actions['edit'] = 'manageable-models/'.$this->getTable().'/edit/'.$this->id;
        }

        if($this->isDeletable()) {
            $actions['delete'] = '/url-here';
        }

        return $actions;
    }

    public static function getNewInstance(): self
    {
        return new self();
    }

    /**
     * getHumanName
     * Gets the human name of the model
     * @return string
     */
    public function getHumanName(bool $plural = true): string
    {
        // Get the table model name that this trait belongs to
        $tableName = (new \ReflectionClass($this))->getName();

        // Remove the namespace
        $tableName = substr($tableName, strrpos($tableName, '\\') + 1);

        // Convert to snake case
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $tableName));

        // Convert to title case
        $tableName = ucwords(str_replace('_', ' ', $tableName));

        return $plural ? Str::plural($tableName) : Str::singular($tableName);
    }

    /**
     * getBrowsableColumns
     * Returns an array of columns (fields) that can be seen in the browse view
     * @return array
     */
    public function getBrowsableColumns(): array
    {
        return [];
    }

    /**
     * getManageableFields
     *
     * @return array ManageableField[]
     */
    public function getManageableFields(): array
    {
        return [];
    }
}
