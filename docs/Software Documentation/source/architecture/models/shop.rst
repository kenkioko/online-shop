Shop Model
~~~~~~~~~~

**Table: shops**

**Class: ``\App\Model\Shop``**

This is model represents a shop. The shop belongs to a user with `seller` role.
The shop can post items and services to be viewed in the application.

A buyer can then make orders.
The shop owner can log in the dashboard to process the orders.


Attributes
~~~~~~~~~~

 * id (primary key)
 * user_id (foreign key)
 * name (string)
 * timestamps


Relationships
~~~~~~~~~~~~~

The user model has the following relationships:
 * User (``App\User``) :-
    One to one relationship.
    Represents the Owner of the shop. Must have a `seller` role.

  * Items (``App\Models\Item``) :-
    One to many relationship.
    Represents the posted items of the shop.

 * Address (``pp\Models\Address``) :-
    One to one relationship.
    Represents the physical addresses for the shop.
    The address may be validated by the admin to ensure authenticity.

