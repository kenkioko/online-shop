<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'category'];

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = ['price', 'images_folder'];

  /**
   * The item's category '1-2-1'.
   */
  public function category()
  {
      return $this->hasOne('App\Category');
  }
}
