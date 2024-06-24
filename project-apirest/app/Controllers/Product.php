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
            $data = [
                'message' => 'success',
                'products_data' => $this->productModel->findAll(),
                
            ];


            return $this->respond($data,200);
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
