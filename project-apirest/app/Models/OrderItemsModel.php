<?php

    namespace App\Models;

    use CodeIgniter\Model;

    class OrderItemsModel extends Model{

        protected $table = 'order_items';
        protected $primaryKeys = 'id';
        protected $allowedFields = ['orderId', 'productId','quantity', 'unit_price'];
        protected $validationRules =[
            'orderId'          => 'required',
            'productId'         => 'required',
            'quantity'          => 'required',
            'unit_price'        => 'required',
        ];

        
        
    }

