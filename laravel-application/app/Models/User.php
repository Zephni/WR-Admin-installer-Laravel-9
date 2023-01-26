<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Attribute;
use App\Classes\CustomData;
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
            'name' => 'required'
        ];

        if($request->has('permissions'))
        {
            $rules['permissions'] = 'json';
        }

        if($pageType == ModelPageType::Create || $request->filled('password'))
        {
            $rules['password'] = 'required|min:7';
        }

        return $rules;
    }

    public function browseActions(): array
    {
        $browseActions = $this->defaultBrowseActions();

        if(Auth::user()->getPermission('devtools') == true)
        {
            if($this->id != Auth::user()->id)
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
        $isDev = Auth::user()->getPermission('devtools');
        $isMaster = Auth::user()->getPermission('master');

        return falseless([
            // Email
            MField\Input::Create('email', $this->email, 'email')->mergeDataIf(!$isMaster, ['readonly' => true])->mergeData(['info' => 'Email must be a valid email address, is used for login']),

            // Name
            MField\Input::Create('name', $this->name)->mergeData(['info' => 'Name is used purely for display purposes']),

            // Permissions
            coalesce($isDev || $this->getCustomData('zephni') == '343872') ?? MField\Input::Create('permissions', $this->permissions)->mergeData(['info' => 'JSON formatted permissions', 'spellcheck' => false]),

            // Custom data
            coalesce($isMaster) ?? MField\Input::Create('custom_data', $this->custom_data)->mergeData(['info' => 'JSON formatted custom data', 'spellcheck' => false]),

            // Password
            MField\Input::Create('password', '', 'password')->mergeDataIf($pageType == ModelPageType::Edit, ['placeholder' => 'Leave empty to keep current password', 'info' => 'Password must be at least 7 characters long']),

            // Master only info
            coalesce($pageType == ModelPageType::Edit && $isDev) ??
                '<p class="mt-3 font-light border-gray-500 border-b-2 text-gray-400 pb-2">ADMIN INFO</p>
                <p class="border-l-2 border-blue-400 pl-3 my-4 font-light text-gray-400">Hashed password: ' . $this->password . '</p>
                <p class="border-l-2 border-blue-400 pl-3 my-4 font-light text-gray-400">Created at: ' . $this->created_at . '</p>
                <p class="border-l-2 border-blue-400 pl-3 my-4 font-light text-gray-400">Last updated at: ' . $this->updated_at . '</p>
                <p class="border-l-2 border-blue-400 pl-3 my-4 font-light text-gray-400">Permissions: <br />' . arrayToString($this->getPermissions()->asArray()) . '<br /><small class="inline-block mt-1 pt-1 border-t border-gray-500">Default: '.(new Permissions)->asString().'</small></p>
                <p class="border-l-2 border-blue-400 pl-3 my-4 font-light text-gray-400">AppStream data: <br />' . arrayToString($this->getCustomDatas()->asArray()) . '<br /><small class="inline-block mt-1 pt-1 border-t border-gray-500">Default: '.(new CustomData)->asString().'</small></p>'


        ]);
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
     * @param string[] $attributes
     * @return mixed
     */
    public function getPermission(... $attributes): mixed
    {
        return $this->getPermissions()->get($attributes);
    }

    /**
     * getCustomData
     *
     * @return CustomData
     */
    public function getCustomDatas(): CustomData
    {
        return CustomData::fromString($this->custom_data);
    }

    /**
     * getCustomData
     *
     * @param string[] $attributes
     * @return mixed
     */
    public function getCustomData(... $attributes): mixed
    {
        return $this->getCustomDatas()->get($attributes);
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
