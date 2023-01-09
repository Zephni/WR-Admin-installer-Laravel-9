<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use SebastianBergmann\CodeCoverage\Report\Xml\Method;
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
    /**
     * Return true if the model can be viewed in the admin panel
     * @return bool
     */
    public function isViewable(): bool
    {
        return false;
    }

    /**
     * Return true if the model can be created in the admin panel
     * @return bool
     */
    public function isCreatable(): bool
    {
        return false;
    }

    /**
     * Return true if the model can be edited in the admin panel
     * @return bool
     */
    public function isEditable(): bool
    {
        return false;
    }

    /**
     * Return true if the model can be deleted in the admin panel
     * @return bool
     */
    public function isDeletable(): bool
    {
        return false;
    }

    /**
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
     * Should return an array of actions that can be performed on the model in the browse view
     * Key is the action name, value is the view / html for the action
     * @return array
     */
    public function browseActions(): array
    {
        return $this->defaultBrowseActions();
    }

    /**
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
     * The amount of models to show per page in the browse view
     * @return int
     */
    public function paginateAmount(): int
    {
        return 15;
    }

    /**
     * Intercept and modify request when the model is created (ran after validation but just before model values are updated)
     * Should use $request->merge() to add or replace values to the request
     * @param  Request $request
     * @return Request
     */
    public function onCreateHook(Request $request): Request
    {
        return $request;
    }

    /**
     * Intercept and modify request when the model is edited (ran after validation but just before model values are updated)
     * Should use $request->merge() to add or replace values to the request
     * @param  Request $request
     * @return Request
     */
    public function onEditHook(Request $request): Request
    {
        return $request;
    }

    /**
     * Intercept and modify request when the model is deleted (ran just before model is deleted)
     * @param  Request $request
     * @return Request
     */
    public function onDeleteHook(Request $request): Request
    {
        return $request;
    }

    /**
     * Gets the human name of the model
     * @param  bool $plural = true
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
     * Returns an array of fields that can be managed in the create and edit views
     * @param ModelPageType $pageType
     * @return array
     */
    public function getManageableFields(ModelPageType $pageType): array
    {
        return [];
    }

    /**
     * Builds an option set for a select field from a relationship
     * @param  string $relationshipModel Pass ModelName::class
     * @param  string $displayField Pass the field from the relationship model to display in the select options (e.g. 'name')
     * @param  callable $filterFunction Pass a function to filter the relationship items. Eg: function($query) { $query->where('name', 'like', '%test%'); }
     * @return array
     */
    public function optionsFromModel(string $model, string $displayField, callable $filterFunction = null): array
    {
        // Check that the model exists, if not throw a hard error
        if(!class_exists($model)) {
            throw new \Exception("Model {$model} does not exist");
        }

        // Initialise the options array
        $options = [];

        // Get the relationship items, filtered if a filter function is provided, otherwise get all items
        $relationshipItems = $filterFunction ?
            $model::where($filterFunction)->get()
            : $model::all();

        // Build the options array from the relationship items
        foreach($relationshipItems as $item)
        {
            $options[$item->{"id"}] = $item->{$displayField};
        }

        // Return the options array
        return $options;
    }

    /**
     * Builds an option set for a select field from a relationship
     * @param Relation $relation Pass the relationship method itself eg. $this->auther() which returns a Relation object like BelongsTo
     * @param  string $displayField Pass the field from the relationship model to display in the select options (e.g. 'name')
     * @param  callable $filterFunction Pass a function to filter the relationship items. Eg: function($query) { $query->where('name', 'like', '%test%'); }
     * @return array
     */
    public function optionsFromRelationship(Relation $relation, string $displayField, callable $filterFunction = null): array
    {
        // Get the class from $relation
        $relatedModel = get_class($relation->getRelated());

        // Return the options array from the relationship model
        return $this->optionsFromModel($relatedModel, $displayField, $filterFunction);
    }


    /**
     * Gets a new instance of the model statically
     * @return self
     */
    public static function getNewInstance(): self
    {
        return new self();
    }

    /**
     * Gets a model instance by id statically
     * @param  int $id
     * @return self
     */
    public static function getInstance(int $id): self
    {
        return self::find($id);
    }
}
