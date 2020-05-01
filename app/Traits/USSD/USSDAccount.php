<?php

namespace App\Traits\USSD;

use Illuminate\Support\Facades\Auth;
Use App\User;

/**
 * Trait to add user account functionality to USSD.
 */
trait USSDAccount
{
    function account_run()
    {
        $ussd = $this->get_active_ussd(true);
        $user = Auth::guard('communication')->User();
        $user_role = implode(', ', $user->getRoleNames()->toArray());
        $user_role = User::getUserRoleByCode($user_role);

        // display user details
        $response_data  = "My Account Details\n";
        $response_data  .= "\n";
        $response_data  .= "Role: " .$user_role['name'] ." \n";
        $response_data  .= "Name: $user->name \n";
        $response_data  .= "Email: $user->email \n";

        // shop details
        if ($user->hasRole('seller')) {
          $shop = $user->shop()->first();
          $response_data  .= "Shop Name: $shop->name \n";
          $response_data  .= "Shop address: $shop->address \n";
        }

        return $this->server_response($response_data, false);
    }
}
