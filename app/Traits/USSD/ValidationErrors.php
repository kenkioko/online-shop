<?php

namespace App\Traits\USSD;

/**
 * The validation errors from validatior if it fails.
 */
trait ValidationErrors
{
    /**
     * Get the validation errors formated with string type.
     *
     * @param \Illuminate\Support\Facades\Validator $validator
     * @return string
     */
    protected function getValidationErrors($validator)
    {
        $response = null;

        if ($validator->errors()->messages()) {
          foreach ($validator->errors()->messages() as $key => $messages) {
            $response .= "\n" .ucfirst($key) ."\n";
            foreach ($messages as $index => $message) {
              $response .= ($index + 1) .". " .$message ."\n";
            }
          }
        }

        return $response;
    }
}
