<?php

namespace App\Models;

use App\Enums\ModelPageType;
use App\Traits\ManageableModel;
use Illuminate\Database\Eloquent\Model;
use App\Classes\ManageableFields as ManageableFields;

class ApplicationConfig extends Model
{
    use ManageableModel;

    protected $table = 'application_config';

    /* ManageableModel traits
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

    public function getHumanName(bool $plural = true): string
    {
        return 'Application Config';
    }

    public function getBrowsableColumns(): array
    {
        return [
            '_key',
            '_value'
        ];
    }

    public function getManageableFields(ModelPageType $pageType): array
    {
        return [
            new ManageableFields\Input('_key', $this->_key),
            new ManageableFields\TextArea('_value', $this->_value),
            new ManageableFields\TextArea('_description', $this->_description),
        ];
    }
}
