<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Attribute;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'email_verified_at' => 'datetime',
        'permissions' => 'json',
    ];

    // permissions attribute setter / getter
    public function permissions():Attribute
    {
        return Attribute::make(
            // Setter turns value into json
            set: fn($value) => $this->attributes['permissions'] = json_encode($value),
            // Getter turns json into array
            get: fn() => json_decode($this->attributes['permissions']) ?? [],
        );
    }

    // hasPermission method
    public function hasPermission(string $permission):bool {
        return isset($this->permissions->$permission) ?? false;
    }

    // getPermission method
    public function getPermission(string $permission) {
        return $this->permissions->$permission ?? false;
    }

    // isMaster method
    public function isMaster():bool {
        return $this->hasPermission('master') && $this->permissions->master === true;
    }

    public function permissionGreaterOrEqualTo(string $permission, int $value):bool {
        if($this->isMaster()) return true;
        return $this->getPermission($permission) >= $value;
    }
}
