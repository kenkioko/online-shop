User Model
----------

**Table: users**

**Class: ``\App\User``**

This model represents the application users.

It extends the ``Illuminate\Foundation\Auth\User`` Authenticatable class
and implements ``Illuminate\Contracts\Auth\MustVerifyEmail``.

It also uses ``Illuminate\Notifications\Notifiable`` and
``Spatie\Permission\Traits\HasRoles`` classes.

The available roles are:
 * Buyer.
 * Seller.
 * Administrator.

The class has ``getUserRoles()`` and ``getUserRolesCodes()`` to get the all roles,
while ``getUserRoleByKey($key)`` to get a role using a key.
This functions are to be moved and implemented as laravel facades.


Attributes
~~~~~~~~~~

 * id (primary key)
 * name (string)
 * email (string, unique)
 * email_verified_at (timestamp)
 * password (string)
 * rememberToken
 * timestamps


Relationships
~~~~~~~~~~~~~

The user model has the following relationships:
 * Phone (``App\Models\Phone``) :-
    One to many relationship.
    Represents the users phone number to enable use of USSD interface.

 * Address (``pp\Models\Address``) :-
    One to many relationship.
    Represents the delivery addresses for the user orders.
    The user can choose a default address.

 * Shop (``App\Models\Shop``) :-
    One to one relationship.
    Represents a user shop.
    This relationship is a must if the user has the `seller` role.

