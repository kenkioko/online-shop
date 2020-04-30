<?php

namespace App\Models;

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
    protected $guarded = [
      'sessionId', 'provider', 'ussd_level', 'level_data'
    ];

    /**
     * The default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'text' => '',
        'ussd_level' => 0,
        'level_data' => '',
    ];

    // /**
    //  * The next ussd level '1-2-M'.
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //  */
    // public function next_level()
    // {
    //     return $this->belongsTo('App\Models\USSD', 'ussd_id');
    // }

    /**
     * The previouso ussd level '1-2-M'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function previous_level()
    {
        return $this->hasOne('App\Models\USSD', 'ussd_id');
    }

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

    /**
     * Overide the main parent.
     * Saves null text to empty string.
     * {@inheritDoc}
     */
    public function save(array $options = [])
    {
        if (is_null($this->text)) {
          $this->text = '';
        }

        return parent::save($options);
    }
}
