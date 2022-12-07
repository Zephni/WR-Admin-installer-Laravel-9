<?php

namespace App\Classes\ManageableModel;

use Illuminate\Database\Eloquent\Model;

abstract class ManageableModel extends Model
{
    public bool $isViewable = true;
    public bool $isCreatable = true;
    public bool $isEditable = true;
    public bool $isDeletable = true;
}
