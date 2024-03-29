<?php

namespace App\Models;

use App\Enums\ModelPageType;
use App\Traits\ManageableModel;
use Illuminate\Database\Eloquent\Model;
use App\Classes\ManageableFields as MField;
use Symfony\Component\HttpFoundation\Request;

class ApplicationConfig extends Model
{
    // Traits
    use ManageableModel;

    // Table name
    protected $table = 'application_config';

    // Config types (used for _type field)
    private $configTypes = [
        'text' => 'Text',
        'json' => 'JSON',
        'array' => 'Array (key => value\n)',
        'boolean' => 'Boolean (true/false)',
        'integer' => 'Integer',
        'float' => 'Float',
        'date' => 'Date (Y-m-d)',
        'datetime' => 'Date & Time (Y-m-d H:i:s)',
        'time' => 'Time (H:i:s)',
        'timestamp' => 'Timestamp',
        'email' => 'Email',
        'url' => 'URL',
    ];

    /* ManageableModel methods
    -----------------------------------------------------------*/
    public function isViewable(): bool
    {
        return true;
    }

    public function isCreatable(): bool
    {
        return true;
    }

    public function isEditable(): bool
    {
        return true;
    }

    public function isDeletable(): bool
    {
        return true;
    }

    public function validationRules(Request $request, ModelPageType $pageType): array
    {
        return [
            '_type' => 'required|in:' . implode(',', array_keys($this->configTypes)),
            '_key' => 'required|min:3|unique:application_config,_key,' . $this->id
        ];
    }

    public function getHumanName(bool $plural = true): string
    {
        return 'Application Config';
    }

    public function getBrowsableColumns(): array
    {
        return [
            '_type',
            '_key',
            '_value'
        ];
    }

    public function getManageableFields(ModelPageType $pageType): array
    {
        return falseless([
            // Type
            MField\Select::Create('_type', $this->_type, $this->configTypes),

            // Key
            MField\Input::Create('_key', $this->_key),

            // Value
            MField\TextArea::Create('_value', $this->_value),

            // Description
            MField\TextArea::Create('_description', $this->_description),
        ]);
    }
}
