<?php


    namespace App\Controllers;
    use CodeIgniter\RESTful\ResourceController;


    class Product extends ResourceController{

        private $productModel;

     
        protected $format = 'json';
        public function __construct(){
            $this->productModel = new \App\Models\ProductsModel();
         }

         private function IsEmpty($id){
            $data = $this->productModel->find($id);
            return ($data === null) ? true : false;
        }

        public function search($info){

            $searchInfo = '%'.$info.'%';
            $data =[
                'message' => 'success',
                'product_by_name' => $this->productModel->like('name', $searchInfo)->findAll(),
                'product_by_description' => $this->productModel->like('description', $searchInfo)->findAll(),
               
            ];

            return $this->respond($data,200);

        }

        public function getById($id){
            $data =[
                'message' => 'success',
                'product_by_id' => $this->productModel->find($id),
            ];
            
            if($data['product_by_id']== null){
                return $this->failNotFound('Product not found',404);
            }

            return $this->respond($data,200);
         }
        public function index(){
          

            $page = $this->request->getVar('page')??1;
            $quantity = $this->request->getVar('quatity')??10;

            $offset = ($page - 1) * $quantity;

            $pages = $this->productModel->orderBy('id', 'DESC')->paginate($quantity, 'default' , $offset);
            $elements = $this->productModel->countAllResults();

            $number = ($page <=0 )? null : $page;
            $totalPages = ($quantity <= 0) ? null : ceil($elements / $quantity);
            $firstPage = ($number === 1);
            $lastPage = ($number === $totalPages);

            $response = [

                'data' =>  [
                'message' => 'success',
                'products_data' => $pages,
                
            ],
                'pagination' =>[
                    'page' => $page,
                    'quantity' => $quantity,
                    'elements' => $elements,
                    'number' => $number,
                    'firstPage' => $firstPage,
                    'lastPage' => $lastPage,
                ]
            ];

            return $this->respond($response,200);

        }
        public function create(){

            /*
            
            $response=[];
            
            $newProduct['name'] = $this->request->getPost('name');
            $newProduct['description'] = $this->request->getPost('description');
            $newProduct['price'] = $this->request->getPost('price');

            $this->productModel->insert($newProduct);
            */

            $rules = $this->validate(
                [
                    'name'          => 'required',
                    'description'   => 'required',
                    'price'         => 'required',
                ]
            );

            if(!$rules){
                $response = [
                    'message' => $this->validator->getErrors()
                ];

                return $this->failValidationErrors($response);
            }

            
            $this->productModel->insert([
                'name' => esc( $this->request->getVar('name')),
                'description'=> esc($this->request->getVar('description')),
                'price' => esc( $this->request->getVar('price')),
            ]);

            $response = [
                'message' => 'success',
            ];


            return $this->respondCreated($response);
                
        }
        public function update($id = null){
            if($this->IsEmpty($id)){

                return $this->failNotFound('Product not found',404);
                
            }else{
                $rules = $this->validate(
                    [
                        'name'          => 'required',
                        'description'   => 'required',
                        'price'         => 'required',
                    ]
                );

                if(!$rules){
                    $response = [
                        'message' => $this->validator->getErrors()
                    ];

                    return $this->failValidationErrors($response);
                }
                
                
                $this->productModel->update($id, [
                    'name'          => esc( $this->request->getVar('name')),
                    'description'   => esc($this->request->getVar('description')),
                    'price'         => esc( $this->request->getVar('price')),
                ]);

                $response = [
                    'message' => 'success',
                    
                ];


                return $this->respond($response, 200);
            }
        }
        public function delete($id = null){

            if($this->IsEmpty($id)){

                return $this->failNotFound('Product not found',404);

            }else{


                $this->productModel->delete($id);

                $response = [
                    'message' => 'Data deleted successfully',
                ];

                return $this->respondDeleted($response);
            }
        }

    }
