<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Attribute;
use App\Traits\ManageableModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Classes\Permissions;
use \App\Classes\ManageableFields as ManageableFields;

class User extends Authenticatable
{
    use ManageableModel, HasApiTokens, HasFactory, Notifiable;

    /* ManageableModel traits
    -----------------------------------------------------------*/
    public function isViewable(): bool
    {
        return Auth::user()->isMaster();
    }

    public function isCreatable(): bool
    {
        return Auth::user()->isMaster();
    }

    public function isEditable(): bool
    {
        return Auth::user()->isMaster();
    }

    public function isDeletable(): bool
    {
        return Auth::user()->isMaster();
    }

    public function getBrowsableColumns(): array
    {
        return [
            'name',
            'email'
        ];
    }

    public function getManageableFields(): array
    {
        $manageableFields = [];
        $manageableFields[] = (new ManageableFields\Input('name', $this->name));
        $manageableFields[] = (new ManageableFields\Input('email', $this->email, 'email'));
        $manageableFields[] = (new ManageableFields\Input('permissions', $this->permissions));

        return $manageableFields;
    }


    /* Attribute modifiers
    -----------------------------------------------------------*/
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    /* Custom methods
    -----------------------------------------------------------*/
    public function getPermissions(): Permissions
    {
        return Permissions::fromString($this->permissions);
    }

    public function getPermission(string $attribute): mixed
    {
        return $this->getPermissions()->$attribute;
    }

    public function isMaster(): bool
    {
        return $this->getPermissions()->master;
    }
}
