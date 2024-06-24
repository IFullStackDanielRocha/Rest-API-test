<?php


    namespace App\Controllers;
    use CodeIgniter\RESTful\ResourceController;
    

    class OrderController extends ResourceController{

        private $orderModel;

        protected $format = 'json';
        public function __construct(){
            $this->orderModel = new \App\Models\OrderModel();
         }

         private function IsEmpty($id){
            $data = $this->orderModel->find($id);
            return ($data === null) ? true : false;
        }
         public function getById($id){
            $data =[
                'message' => 'success',
                'order_by_id' => $this->orderModel->find($id),
            ];
            
            if($data['order_by_id']== null){
                return $this->failNotFound('Order  not found',404);
            }

            return $this->respond($data,200);
         }

        public function index(){
            $data = [
                'message' => 'success',
                'Order_data' => $this->orderModel->findAll(),
                
            ];


            return $this->respond($data,200);
        }
        public function create(){
        
            

            $rules = $this->validate(
                [
                    'customerId'          => 'required',
                    'status'              => 'required'
                ]
            );

            if(!$rules){
                $response = [
                    'message' => $this->validator->getErrors()
                ];

                return $this->failValidationErrors($response);
            }

            
            $this->orderModel->insert([
                'customerId'          => esc( $this->request->getVar('customerId')),
                'order_date'          => esc($this->request->getVar('order_date')),
                'total'               => esc($this->request->getVar('total')),
                'status'              => esc($this->request->getVar('status')),

            ]);

            $response = [
                'message' => 'success',
            ];


            return $this->respondCreated($response);
                
        }
        public function update($id = null){
            if($this->IsEmpty($id)){
                $response = [
                    'message' => 'Error: Order  not found',
                ];

                return $this->respond($response,404);

            }else{
                $rules = $this->validate(
                    [
                        'customerId'          => 'required',
                        'status'              => 'required'            

                    ]
                );

                if(!$rules){
                    $response = [
                        'message' => $this->validator->getErrors()
                    ];

                    return $this->failValidationErrors($response);
                }
                
               
                $this->orderModel->update($id, [
                    'customerId'          => esc( $this->request->getVar('customerId')),
                    'order_date'          => esc($this->request->getVar('order_date')),
                    'total'               => esc($this->request->getVar('total')),
                    'status'              => esc($this->request->getVar('status')),

                ]);

                $response = [
                    'message' => 'success',
                    
                ];


                return $this->respond($response, 200);
            }
        }
        public function del($id){
            
           $data = $this->orderModel->find($id);

           if($this->IsEmpty($id)){
                $response = [
                    'message' => 'Error: order_ not found',
                ];

                return $this->respond($response,404);
           } else{
                $this->orderModel->delete($id);

                $response = [
                'message' => 'Data deleted successfully',
                ];

                return $this->respondDeleted($response);
           }


            
        }

    }



