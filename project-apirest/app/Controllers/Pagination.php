<?php

    namespace App\Controllers;

    use CodeIgniter\RESTful\ResourceController;
    use CodeIgniter\API\ResponseTrait;
    use App\Models\ProductsModel;
    class Pagination extends ResourceController{

        use ResponseTrait;
        
        public $pageModel;
        public function index(){
            
            $page = $this->request->getVar('page')??1;
            $quantity = $this->request->getVar('quatity')??3;

            $offset = ($page - 1) * $quantity;

            $pages = $this->pageModel->orderBy('id', 'DESC')->paginate($quantity, 'default' , $offset);
            $elements = $this->pageModel->countAllResults();

            $number = ($page <=0 )? null : $page;
            $totalPages = ($quantity <= 0) ? null : ceil($elements / $quantity);
            $firstPage = ($number === 1);
            $lastPage = ($number === $totalPages);

            $response = [
                'data' => $pages,
                'pagination' =>[
                    'page' => $page,
                    'quantity' => $quantity,
                    'elements' => $elements,
                    'number' => $number,
                    'firstPage' => $firstPage,
                    'lastPage' => $lastPage,
                ]
            ];

            return $this->respond($response);
        }

    }


