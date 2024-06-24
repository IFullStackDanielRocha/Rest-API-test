<?php

    namespace App\Models;

    use CodeIgniter\Model;

    class OrderModel extends Model{

        protected $table = 'orders';
        protected $primaryKeys = 'id';
        protected $allowedFields = ['customerId', 'order_date', 'total', 'status'];
        protected $validationRules =[
            'customerId'           => 'required',
            'status'                => 'required',
        ];

        
        
    }

