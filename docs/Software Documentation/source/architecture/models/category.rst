Category Model
~~~~~~~~~~~~~~

**Table: categories**

**Class: ``\App\Model\Category``**

This is model represents a category in which the all posted items and services belong to.
Each item and service must have a category.

The categories are created by the users with `admin` role.
The categories can have sub categories.


Attributes
~~~~~~~~~~

 * id (primary key)
 * parent_category_id (foreign key)
 * name (string)
 * timestamps


Relationships
~~~~~~~~~~~~~

The user model has the following relationships:
 * parent_category (``App\Models\Category``) :-
    One to one relationship.
    Represents the parent category of the category.

 * sub_categories (``App\Models\Category``) :-
    One to Many relationship.
    Represents the subcategories for the categories.

  * Items (``App\Models\Item``) :-
    One to many relationship.
    Represents the posted items belonging to the category.

  * Services (``App\Models\Services``) :-
    One to many relationship.
    Represents the posted services belonging to the category.

