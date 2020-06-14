Order Model
~~~~~~~~~~~

**Table: orders**

**Class: ``App\Model\Order``**

This is model represents an order made by a user with the `buyer` role.
The order contains items and the total price of all the items.

The user, owner of the shop, processes the orders received via the dashboard.
The available statuses for the order processing are:

 * items_in_cart :- Order has items in cart but the buyer has not completed the order.
 * order_made :- Order has been made by the buyer.
 * processing
 * items_in_cart



 '' => "ITEMS IN CART",
    'order_made' => "ORDER MADE",
    'processing' => "PROCESSING ORDER",
    'completed' => "ORDER COMPLETED",
    'completed_partial' => "ORDER PARTIALLY COMPLETED",


Attributes
~~~~~~~~~~

 * id (primary key)
 * order_no (uuid)
 * user_id (foreign key)
 * total (unsigned decimal)
 * order_date (dateTime)
 * status (enum [])
 * category_id (foreign key)
 * shop_id (foreign key)
 * timestamps


 $table->bigIncrements('id');
            $table->uuid('order_no');
            $table->unsignedBigInteger('user_id');
            $table->unsignedDecimal('total', 8, 2)->default(0);
            $table->dateTime('order_date')->nullable();
            $table->enum('status', Order::getStatusAll(true))->default('items_in_cart');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');


Relationships
~~~~~~~~~~~~~

The user model has the following relationships:
 * category (``App\Models\Category``) :-
    One to one relationship.
    Represents the category which the item belongs to.

  * shop (``App\Models\Shop``) :-
    One to many relationship.
    Represents the shop, the owner of the item.

