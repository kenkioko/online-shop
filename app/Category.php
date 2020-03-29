<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name'];

  /**
   * The parrent category '1-2-M'.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function parent_category()
  {
      return $this->belongsTo('App\Category', 'parent_category_id');
  }

  /**
   * The subcategories.
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function sub_categories()
  {
      return $this->hasMany('App\Category', 'parent_category_id');
  }

  /**
   * The subcategories.
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function items()
  {
      return $this->hasMany('App\Item');
  }
}
