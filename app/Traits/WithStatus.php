<?php

namespace App\Traits;

/**
 * Gives an option to convert status key to value.
 * It also generates the keys for the status.
 * 'self::STATUS' is assumed to be a constant associative array
 * which is defined on the inheriting class.
 */
trait WithStatus
{
  /**
   * Returns the 'self::STATUS' constant array.
   * The STATUS const can be overriden to fit multiple models.
   * Can be used to retreive keys only.
   *
   * @param bool $keys_only // determines if  status key or value is returned
   * @return array
   */
  public static function getStatusAll($keys_only=false)
  {
      $status = self::STATUS;

      if ($keys_only) {
        $status = [];

        foreach(self::STATUS as $key => $value) {
          array_push($status, $key);
        }
      }

      return $status;
  }

  /**
   * Returns a particular status.
   * Either the key or the value is returned specified by a parameter.
   * By default returns the key.
   *
   * @param string $status
   * @param bool $key_only    // determines if  status key or value is returned
   * @return array
   */
  public static function getStatus($status, $key_only=true)
  {
      $status_ = self::STATUS[$status];

      if ($key_only) {
        foreach(self::STATUS as $key => $value) {
          if ($key == $status) {
            $status_ = $key;
            break;
          }
        }
      }

      return $status_;
  }
}
