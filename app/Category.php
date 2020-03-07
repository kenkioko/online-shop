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
   * @return void
   */
  public function parent_category()
  {
      return $this->belongsTo('App\Category', 'parent_category_id');
  }
}
