<?php


    namespace App\Controllers;
    use CodeIgniter\RESTful\ResourceController;


    class CustomerController extends ResourceController{

        private $customerModel;




        protected $format = 'json';
        public function __construct(){
            $this->customerModel = new \App\Models\CustomerModel();
         }

         private function IsEmpty($customer_id){
            $data = $this->customerModel->find($customer_id);
            return ($data === null) ? true : false;
        }
         public function getById($customer_id){
            $data =[
                'message' => 'success',
                'customer_by_id' => $this->customerModel->find($customer_id),
            ];
            
            if($data['customer_by_id']== null){
                return $this->failNotFound('customer not found',404);
            }

            return $this->respond($data,200);
         }


         public function search($info){

            $searchInfo = '%'.$info.'%';
            $data =[
                'message' => 'success',
                'customer_by_name' => $this->customerModel->like('name', $searchInfo)->findAll(),
            ];

            return $this->respond($data,200);

        }
        public function index(){
            $page = $this->request->getVar('page')??1;
            $quantity = $this->request->getVar('quatity')??10;

            $offset = ($page - 1) * $quantity;

            $pages = $this->customerModel->orderBy('id', 'DESC')->paginate($quantity, 'default' , $offset);
            $elements = $this->customerModel->countAllResults();

            $number = ($page <=0 )? null : $page;
            $totalPages = ($quantity <= 0) ? null : ceil($elements / $quantity);
            $firstPage = ($number === 1);
            $lastPage = ($number === $totalPages);

            $response = [

                'data' =>  [
                'message' => 'success',
                'customer_data' => $pages,
                
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
        
            

            $rules = $this->validate(
                [
                    'name'          => 'required',
                    'email'           => 'required',
                    'cpf'           => 'required'
                ]
            );

            if(!$rules){
                $response = [
                    'message' => $this->validator->getErrors()
                ];

                return $this->failValidationErrors($response);
            }

            
            $this->customerModel->insert([
                'name'          => esc( $this->request->getVar('name')),
                'email'          => esc( $this->request->getVar('email')),
                'cpf'           => esc($this->request->getVar('cpf')),

            ]);

            $response = [
                'message' => 'success',
            ];


            return $this->respondCreated($response);
                
        }
        public function update($customer_id = null){
            if($this->IsEmpty($customer_id)){
                $response = [
                    'message' => 'Error: customer not found',
                ];

                return $this->respond($response,404);

            }else{
                $rules = $this->validate(
                    [
                        'name'          => 'required',
                        'email'         => 'required',
                        'cpf'           => 'required',
                    ]
                );

                if(!$rules){
                    $response = [
                        'message' => $this->validator->getErrors()
                    ];

                    return $this->failValidationErrors($response);
                }
                
                
                $this->customerModel->update($customer_id, [
                    'name'          => esc( $this->request->getVar('name')),
                    'email'          => esc( $this->request->getVar('email')),
                    'cpf'           => esc($this->request->getVar('cpf')),

                ]);

                $response = [
                    'message' => 'success',
                    
                ];


                return $this->respond($response, 200);
            }
        }
        public function del($customer_id){
            
           $data = $this->customerModel->find($customer_id);

           if($this->IsEmpty($customer_id)){
                $response = [
                    'message' => 'Error: customer not found',
                ];

                return $this->respond($response,404);
           } else{
                $this->customerModel->delete($customer_id);

                $response = [
                'message' => 'Data deleted successfully',
                ];

                return $this->respondDeleted($response);
           }


            
        }

    }



