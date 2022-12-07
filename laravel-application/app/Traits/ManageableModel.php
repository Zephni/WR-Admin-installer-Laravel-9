<?php

namespace App\Traits;

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

    public function getTableName(): string
    {
        // Get the table model name that this trait belongs to
        return (new \ReflectionClass($this))->getName();
    }

    public function getHumanName(): string
    {
        // Get the table model name that this trait belongs to
        $tableName = (new \ReflectionClass($this))->getName();

        // Remove the namespace
        $tableName = substr($tableName, strrpos($tableName, '\\') + 1);

        // Convert to snake case
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $tableName));

        // Convert to title case
        $tableName = ucwords(str_replace('_', ' ', $tableName));

        return $tableName;
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
