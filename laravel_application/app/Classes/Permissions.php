<?php

namespace App\Classes;

class Permissions extends JSONFormattedField
{
    public bool $devtools = false;
    public bool $master = false;
    public bool $admin = false;
}
