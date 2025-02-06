<?php

namespace Config;  // Add this line to define the namespace

use CodeIgniter\Config\BaseConfig;

class Roles extends BaseConfig {
    public $redirect_urls = [
        1 => 'super-admin',
        2 => 'hotel-owner',
        3 => 'hotel-manager',
        4 => 'front-office',
        5 => 'housekeeping',
        6 => 'kitchen',
        7 => 'staff',
        8 => 'customer',
    ];
}
