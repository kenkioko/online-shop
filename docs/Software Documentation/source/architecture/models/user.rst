User Model
----------

**Table: users**

This model represents the application users.

It extends the ``Illuminate\Foundation\Auth\User`` Authenticatable class
and implements ``Illuminate\Contracts\Auth\MustVerifyEmail``.

It also uses ``Illuminate\Notifications\Notifiable`` and
``Spatie\Permission\Traits\HasRoles`` classes.

The available roles are ::

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

The class has ``getUserRoles()`` and ``getUserRolesCodes()`` to get the all roles,
while ``getUserRoleByKey($key)`` to get a role using a key.


Attributes
~~~~~~~~~~

 * id (primary)
 * name (string)
 * email (string, unique)
 * email_verified_at (timestamp)
 * password (string)
 * rememberToken
 * timestamps


Relationships
~~~~~~~~~~~~~
We have relationships ::

    /**
     * The user's phone '1-2-M'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function phone()
    {
        return $this->hasMany('App\Models\Phone');
    }

    /**
     * The user's address '1-2-1'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasMany('App\Models\Address');
    }

    /**
     * The user's shop if role='seller'. '1-2-1'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shop()
    {
        return $this->hasOne('App\Models\Shop');
    }


