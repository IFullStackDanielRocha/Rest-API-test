<?php

namespace App\Models;

use CodeIgniter\Model;

class SuperAdminModel extends Model
{
    protected $table            = 'superadmin';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['email', 'password',];

    protected $validationRules      = [
        'user' => 'required',
        'email' => 'required',
        'password' => 'required',
        'isActive' => 'required',
    ];
 

}
