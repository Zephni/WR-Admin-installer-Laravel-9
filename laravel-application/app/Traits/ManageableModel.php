<?php

namespace App\Traits;

use Str;
use Symfony\Component\HttpFoundation\Request;
use \App\Enums\ModelPageType;

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
     * validationRules
     * Pass the validation rules for this model (field => rules), called on both creation and editing of the model
     * @param  mixed $request
     * @param  mixed $pageType
     * @return array
     */
    public function validationRules(Request $request, ModelPageType $pageType): array
    {
        return [
            // 'field' => 'rules'
        ];
    }

    /**
     * browseActions
     * Should return an array of actions that can be performed on the model in the browse view
     * Key is the action name, value is the view / html for the action
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
     * Key is the action name, value is the view / html for the action
     * @return array
     */
    public function defaultBrowseActions(): array
    {
        $actions = [];

        if($this->id == null)
        {
            $this->id = 0;
        }

        if($this->isEditable()) {
            $actions['edit'] = view('components.admin.button', [
                'type' => 'primary',
                'text' => 'Edit',
                'href' => route('admin.manageable-models.edit', [
                    'table' => $this->getTable(),
                    'id' => $this->id
                ])
            ]);
        }

        if($this->isDeletable()) {
            $actions['delete'] = view('components.admin.button', [
                'confirm' => 'Are you sure you want to delete this?',
                'type' => 'danger',
                'text' => 'Delete',
                'href' => route('admin.manageable-models.delete', [
                    'table' => $this->getTable(),
                    'id' => $this->id
                ])
            ]);
        }

        return $actions;
    }

    /**
     * paginateAmount
     * The amount of models to show per page in the browse view
     * @return int
     */
    public function paginateAmount(): int
    {
        return 15;
    }

    /**
     * onCreateHook
     * Intercept and modify request when the model is created (ran after validation but just before model values are updated)
     * Should use $request->merge() to add or replace values to the request
     * @return Request
     */
    public function onCreateHook(Request $request): Request
    {
        return $request;
    }

    /**
     * onEditHook
     * Intercept and modify request when the model is edited (ran after validation but just before model values are updated)
     * Should use $request->merge() to add or replace values to the request
     * @return Request
     */
    public function onEditHook(Request $request): Request
    {
        return $request;
    }

    /**
     * onDeleteHook
     * Intercept and modify request when the model is deleted (ran just before model is deleted)
     * @return Request
     */
    public function onDeleteHook(Request $request): Request
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
        // Default columns to show in the browse view if method is not overridden
        $showMaxColumns = 2;

        // Get the columns from the table
        $columns = collect($this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable()));

        // Remove some default laravel columns
        $columns = $columns->reject(function ($column) use ($columns, $showMaxColumns) {
            if(count($columns) <= $showMaxColumns) return false;
            return in_array($column, ['id', 'created_at', 'updated_at', 'deleted_at', 'password', 'email_verified_at', 'remember_token']);
        });

        // Take up to a maximum of $showMaxColumns columns depending on how many columns are left
        $columns = $columns->take(min($showMaxColumns, $columns->count()));

        // Return the columns as an array
        return $columns->toArray();
    }

    /**
     * getSearchableColumns
     * Returns an array of columns (fields) that can be searched in the browse view
     * @return array
     */
    public function getSearchableColumns(): array
    {
        // Get a collection of all column names from the table
        $allColumns = collect($this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable()));

        // Reject some default laravel columns
        $filteredColumns = $allColumns->reject(function($column) {
            return in_array($column, ['id', 'created_at', 'updated_at', 'deleted_at', 'password']);
        });

        // Return the filtered columns as an array
        return $filteredColumns->toArray();
    }

    /**
     * getManageableFields
     * Returns an array of fields that can be managed in the create and edit views
     * @param string $pageType (Can be 'any', 'browse', 'create' or 'edit')
     * @return array
     */
    public function getManageableFields(ModelPageType $pageType): array
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
