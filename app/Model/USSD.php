<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class USSD extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ussds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'phoneNumber', 'networkCode', 'serviceCode', 'text'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['sessionId', 'provider'];

    /**
     * The available USSD service providers.
     *
     * @var array
     */
    protected const PROVIDERS = [
        'africastkng'
    ];

    /**
     * return the user roles.
     *
     * @return array
     */
    public static function getProviders()
    {
        return self::PROVIDERS;
    }
}
