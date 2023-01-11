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
use \App\Classes\ManageableFields as MField;
use Symfony\Component\HttpFoundation\Request;
use \App\Enums\ModelPageType;

class User extends Authenticatable
{
    // Traits
    use ManageableModel, SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    // Table name
    protected $table = 'users';

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

    /* ManageableModel methods
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
        // User can edit themselves, but only master can edit other users
        return $this->id == Auth::user()->id || Auth::user()->isMaster();
    }

    public function isDeletable(): bool
    {
        if($this->id == Auth::user()->id)
        {
            return false;
        }

        return Auth::user()->isMaster();
    }

    public function validationRules(Request $request, ModelPageType $pageType): array
    {
        $rules = [
            'email' => 'required|email|unique:users,email,' . $this->id,
            'name' => 'required',
            'permissions' => 'required|json'
        ];

        if($pageType == ModelPageType::Create || $request->filled('password'))
        {
            $rules['password'] = 'required|min:7';
        }

        return $rules;
    }

    public function browseActions(): array
    {
        $browseActions = $this->defaultBrowseActions();

        if(Auth::user()->getPermission('zephni') == true)
        {
            $browseActions = array_merge($browseActions, [
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

        return $browseActions;
    }

    public function onCreateHook(Request $request): Request
    {

        return $request->merge([
            'permissions' => $request->filled('permissions') ? $request->input('permissions') : (new Permissions())->asString(),
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

    public function getManageableFields(ModelPageType $pageType): array
    {
        $manageableFields = [];
        $manageableFields[] = MField\Input::Create('email', $this->email, 'email')->mergeData(['readonly' => !Auth::user()->getPermission('zephni')]);
        $manageableFields[] = MField\Input::Create('name', $this->name);
        $manageableFields[] = MField\Input::Create('permissions', $this->permissions);

        if($pageType == ModelPageType::Create)
        {
            $manageableFields[] = MField\Input::Create('password', '', 'password');
        }
        else if($pageType == ModelPageType::Edit)
        {
            $manageableFields[] = MField\Input::Create('password', '', 'password')->mergeData(['placeholder' => 'Leave empty to keep current password']);
            $manageableFields[] = '<p class="py-3">Hashed password: ' . $this->password . '</p>';
            $manageableFields[] = '<p class="py-3">Created at: ' . $this->created_at . '</p>';
            $manageableFields[] = '<p class="py-3">Last updated at: ' . $this->updated_at . '</p>';
        }

        return $manageableFields;
    }

    /* Custom methods
    -----------------------------------------------------------*/
    /**
     * getPermissions
     *
     * @return Permissions
     */
    public function getPermissions(): Permissions
    {
        return Permissions::fromString($this->permissions);
    }

    /**
     * getPermission
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getPermission(string $attribute): mixed
    {
        // Check if the attribute exists
        if(!property_exists($this->getPermissions(), $attribute))
        {
            return false;
        }

        // Otherwise return the attribute
        return $this->getPermissions()->$attribute;
    }

    /**
     * isMaster
     *
     * @return bool
     */
    public function isMaster(): bool
    {
        return $this->getPermissions()->master;
    }
}
