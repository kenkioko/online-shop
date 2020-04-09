<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The available user level 'roles'.
     *
     * @var array
     */
    protected const ROLES = [
        "1"  => [
          "code" => "buyer",
          "name" => "Normal Customer",
        ],
        "2"  => [
          "code" => "seller",
          "name" => "Business",
        ],
        "3"  => [
          "code" => "admin",
          "name" => "Administrator",
        ],
    ];

    /**
     * return the user roles.
     *
     * @return array
     */
    public static function getUserRoles()
    {
        return self::ROLES;
    }

    /**
     * return the user roles as an array of user codes.
     *
     * @return array
     */
    public static function getUserRolesCodes()
    {
        $roles = [];
        foreach (self::ROLES as $id => $role) {
          array_push($roles, $role['code']);
        }

        return $roles;
    }

    /**
     * return the user roles.
     *
     * @return array
     */
    public static function getUserRoleByKey($key)
    {
        $role = self::ROLES[$key];
        $role['key'] = $key;

        return $role;
    }

    /**
     * return the user roles.
     * calls getUserRoleByKey()
     *
     * @return array
     */
    public static function getUserRoleByCode($code)
    {
        $role = null;
        $key = array_search($code, array_column(self::ROLES, 'code'));

        if (is_int($key)) {
          $role = self::getUserRoleByKey('' .($key + 1));
        }

        return $role;
    }

    /**
     * The user's shop if role='seller'. '1-2-1'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shop()
    {
        return $this->hasOne('App\Model\Shop');
    }

    /**
     * The user's phone '1-2-1'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function phone()
    {
        return $this->hasMany('App\Model\Phone');
    }
}
