<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Attribute;
use App\Traits\ManageableModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use App\Classes\Permissions;
use \App\Classes\ManageableFields as ManageableFields;
use Symfony\Component\HttpFoundation\Request;

class User extends Authenticatable
{
    use ManageableModel, SoftDeletes, HasApiTokens, HasFactory, Notifiable;

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

    public function browseActions(): array
    {
        $browseActions = $this->defaultBrowseActions();
        return array_merge($browseActions, [
            'login' => view('components.admin.button', [
                'confirm' => 'Login as '.$this->email.'?',
                'type' => 'secondary',
                'text' => 'Login',
                'href' => route('admin.login-as', [
                    'userid' => $this->id
                ])
            ])
        ]);
    }

    public function onCreateHook(Request $request): Request
    {
        return $request->merge([
            'permissions' => (new Permissions())->asString(),
            'password' => Hash::make($request->input('password'))
        ]);
    }

    public function onEditHook(Request $request): Request
    {
        return $request->merge([
            'password' => ($request->has('password') && !empty($request->input('password'))) ? Hash::make($request->input('password')) : $this->password
        ]);
    }

    public function getBrowsableColumns(): array
    {
        return [
            'name',
            'email'
        ];
    }

    public function getManageableFields(string $pageType = 'any'): array
    {
        $manageableFields = [];
        $manageableFields[] = new ManageableFields\Input('name', $this->name);
        $manageableFields[] = new ManageableFields\Input('email', $this->email, 'email');
        $manageableFields[] = new ManageableFields\Input('permissions', $this->permissions);

        if($pageType == 'create')
        {
            $manageableFields[] = new ManageableFields\Input('password', '', 'password');
        }
        if($pageType == 'edit')
        {
            $manageableFields[] = (new ManageableFields\Input('password', '', 'password'))->options(['placeholder' => 'Leave empty to keep current password']);
            $manageableFields[] = '<p class="py-3">Hashed password: ' . $this->password . '</p>';
            $manageableFields[] = '<p class="py-3">Created at: ' . $this->created_at . '</p>';
            $manageableFields[] = '<p class="py-3">Last updated at: ' . $this->updated_at . '</p>';
        }

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
