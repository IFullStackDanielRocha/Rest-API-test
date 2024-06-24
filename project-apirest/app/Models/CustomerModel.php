<?php

    namespace App\Models;

    use CodeIgniter\Model;

    class CustomerModel extends Model{

        protected $table = 'customers';
        protected $primaryKeys = 'id';
        protected $allowedFields = ['name', 'email','cpf'];
        protected $validationRules =[
            'name'  => 'required',
            'email' => 'required',
            'cpf'   => 'required',
        ];

        
        
    }

