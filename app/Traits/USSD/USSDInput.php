<?php

namespace App\Traits\USSD;

use App\Models\USSD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * The active ussd session and user data.
 */
trait USSDInput
{
    /**
     * The active ussd session.
     *
     * @var \App\Models\USSD
     */
    private $ussd;

    /**
     * Initializes the active ussd session.
     *
     * @param array  $ussd_data
     * @param  string  $provider
     * @return object
     */
    protected function initialize_ussd(array  $ussd_data, string  $provider) : object
    {
        $validator = $this->validator($ussd_data);
        if ($validator->fails()) {
          $response = "An error ocurred while processing the data.\n";
          $response .= $this->getValidationErrors($validator);

          // return failed validation
          return (object) [
            'status' => false,
            'response' => $response,
          ];
        }

        // create the active ussd 'do not save'
        $validated = $validator->validate();
        $this->set_active_ussd($validated, $provider);

        // set the ussd level data
        $this->ussd->ussd_level = 0;
        $ussd = $this->get_active_ussd();

        // set the ussd level data and return
        return (object) [
          'status' => true,
          'ussd' => $ussd,
        ];
    }

    /**
     * The ussd has gone up one level.
     *
     * @return App\Models\USSD
     */
    protected function ussd_level_up() : USSD
    {
        // do not overwrite saved ussd
        if ($this->ussd->id) {
          $ussd = new USSD($this->ussd->toArray());
          $ussd->sessionId = $this->ussd->sessionId;
          $ussd->provider = $this->ussd->provider;
          $ussd->ussd_level = $this->ussd->ussd_level;
          $ussd->level_data = $this->ussd->level_data;

          $this->ussd = $ussd;
        }

        // set next level
        $this->ussd->ussd_level += 1;
        return $this->get_active_ussd();
    }

    /**
     * Sets the active ussd (does not save to db).
     *
     * @param array $validated
     * @param string $provider
     * @return App\Models\USSD
     */
    private function set_active_ussd(array $validated, string $provider) : USSD
    {
        $ussd = new USSD($validated);
        $ussd->sessionId = $validated["sessionId"];
        $ussd->provider = $provider;
        $this->ussd = $ussd;

        return $this->ussd;
    }

    /**
     * Returns the active ussd.
     *
     * @param bool $save_to_db
     * @return App\Models\USSD
     */
    protected function get_active_ussd(bool $save_to_db = false) : USSD
    {
        $level_data = $this->get_saved_ussd();
        $current_level = $level_data->current_level;

        // return current level data from db
        if ($level_data->current_level->count() > 0) {
          $current_level = $level_data->current_level->first();
          $same_level = ($this->ussd->text == $current_level->text);

          if ($same_level or $current_level->level_data != '') {
            return $level_data->current_level->first();
          }
        }

        // save current level data
        return DB::transaction(function () use ($level_data, $save_to_db) {
          // set level data
          $truncated_data = ltrim($this->ussd->text, $level_data->truncated_data);
          $this->ussd->level_data = trim($truncated_data);

          if ($save_to_db) {
            $this->ussd->save();
            // save previous level
            $previous_level = ($level_data->current_level->count() > 0)
                ? $level_data->current_level->first()
                : $level_data->previous_levels->first();

            if ($previous_level) {
              $this->ussd->previous_level()->save($previous_level);
            }
          }

          return $this->ussd;
        });
    }

    /**
     * Get previous ussd data in the same session saved in db.
     * Returns an object with the truncated data and previous ussds.
     *
     * @param int $level
     * @param string $session_id
     * @return object
     */
    private function get_saved_ussd(int $level = null, string $session_id = null) : object
    {
        // get current level if saved in db
        $current_level = Ussd::where('sessionId', $session_id ? $session_id : $this->ussd->sessionId)
            ->where('ussd_level', $level ? $level : $this->ussd->ussd_level)
            ->latest()->get();

        // get previous levels saved in db
        $previous_levels = Ussd::where('sessionId', $session_id ? $session_id : $this->ussd->sessionId)
            ->where('ussd_level', '!=', $level ? $level : $this->ussd->ussd_level)
            ->latest()->get();

        // set trancated text
        $truncated_data = ($previous_levels->count() > 0)
            ? $previous_levels->first()->text
            : '';

        return (object) [
          'current_level' => $current_level,
          'previous_levels' => $previous_levels,
          'truncated_data' => $truncated_data,
        ];
    }

    /**
     * Return the validation rules for the africastkng api.
     *
     * @param  array  $data   \\ Data to validate from the api
     * @return Illuminate\Support\Facades\Validator
     */
    private function validator($data)
    {
        $validator = Validator::make($data, [
          'sessionId' => ['required', 'string', 'max:255'],
          'phoneNumber' => ['required', 'string', 'max:255'],
          'networkCode' => ['required', 'string', 'max:255'],
          'serviceCode' => ['required', 'string', 'max:255'],
          'text' => ['nullable', 'string', 'max:255'],
        ]);

        return $validator;
    }

}
