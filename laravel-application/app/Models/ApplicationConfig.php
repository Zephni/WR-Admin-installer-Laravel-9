<?php

namespace App\Models;

use App\Traits\ManageableModel;
use Illuminate\Database\Eloquent\Model;

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
}
