<?php

namespace App\Classes;

class CustomData extends JSONFormattedField
{
    public array $appstream = [
        'enabled'       => false,   // Set to true to enable appstream for a user
        'user'          => '',      // The user to use for appstream (eg. FirstName.LastName)
        'stack_fleet'   => '',      // The stack fleet to use for appstream (eg. ParagonCloudProduction)
    ];
}
