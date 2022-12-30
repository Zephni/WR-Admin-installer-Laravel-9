<?php

namespace App\Traits;

use Str;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * browseActions
     * Should return an array of actions that can be performed on the model in the browse view
     * Key is the action name, value is the url
     * @return array
     */
    public function browseActions(): array
    {
        return $this->defaultBrowseActions();
    }

    /**
     * defaultBrowseActions
     * Should never override this method in the model
     * Should return an array of actions that can be performed on the model in the browse view
     * Key is the action name, value is the url
     * @return array
     */
    public function defaultBrowseActions(): array
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

    /**
     * onCreateHook
     * Intercept and modify request when the model is created (ran just before model values are updated)
     * Should use $request->merge() to add or replace values to the request
     * @return Request
     */
    public function onCreateHook(Request $request): Request
    {
        return $request;
    }

    /**
     * onEditHook
     * Intercept and modify request when the model is edited (ran just before model values are updated)
     * Should use $request->merge() to add or replace values to the request
     * @return Request
     */
    public function onEditHook(Request $request): Request
    {
        return $request;
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
     * Returns an array of fields that can be managed in the create and edit views
     * @param string $pageType (Can be 'any', 'browse', 'create' or 'edit')
     * @return array
     */
    public function getManageableFields(string $pageType = 'any'): array
    {
        return [];
    }

    /**
     * getNewInstance
     * Gets a new instance of the model statically
     * @return self
     */
    public static function getNewInstance(): self
    {
        return new self();
    }
}