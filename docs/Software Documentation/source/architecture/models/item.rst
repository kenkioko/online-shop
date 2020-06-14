Item Model
~~~~~~~~~~

**Table: items**

**Class: ``\App\Model\Item``**

This is model represents an item posted buy a shop owner through the application dashboard.
The item must belong to a categories.


Attributes
~~~~~~~~~~

 * id (primary key)
 * name (string)
 * description (text)
 * images_folder (uuid)
 * stock (unsigned integer)
 * price (unsigned decimal)
 * discount_amount (unsigned decimal)
 * discount_percent (unsigned integer)
 * category_id (foreign key)
 * shop_id (foreign key)
 * timestamps


Relationships
~~~~~~~~~~~~~

The user model has the following relationships:
 * category (``App\Models\Category``) :-
    One to one relationship.
    Represents the category which the item belongs to.

  * shop (``App\Models\Shop``) :-
    One to many relationship.
    Represents the shop, the owner of the item.

