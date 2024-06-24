<?php

    namespace App\Models;

    use CodeIgniter\Model;

    class ProductsModel extends Model{

        protected $table = 'products';
        protected $primaryKeys = 'id';
        protected $allowedFields = ['name', 'description', 'price'];
        protected $validationRules =[
            'name'          => 'required',
            'description'   => 'required',
            'price'         => 'required',
        ];

        
        
    }

