<?php


    namespace App\Controllers;
    use CodeIgniter\RESTful\ResourceController;


    class OrderItemsController extends ResourceController{

        private $orderItemsModel;




        protected $format = 'json';
        public function __construct(){
            $this->orderItemsModel = new \App\Models\OrderItemsModel();
         }

         private function IsEmpty($id){
            $data = $this->orderItemsModel->find($id);
            return ($data === null) ? true : false;
        }
         public function getById($id){
            $data =[
                'message' => 'success',
                'order_items_by_id' => $this->orderItemsModel->find($id),
            ];
            
            if($data['order_items_by_id']== null){
                return $this->failNotFound('Order items not found',404);
            }

            return $this->respond($data,200);
         }

        public function index(){
            $page = $this->request->getVar('page')??1;
            $quantity = $this->request->getVar('quatity')??10;

            $offset = ($page - 1) * $quantity;

            $pages = $this->orderItemsModel->orderBy('id', 'DESC')->paginate($quantity, 'default' , $offset);
            $elements = $this->orderItemsModel->countAllResults();

            $number = ($page <=0 )? null : $page;
            $totalPages = ($quantity <= 0) ? null : ceil($elements / $quantity);
            $firstPage = ($number === 1);
            $lastPage = ($number === $totalPages);

            $response = [

                'data' => [
                'message' => 'success',
                'order_items_data' => $pages,
                
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
                    'orderId'          => 'required',
                    'productId'        => 'required',
                    'quantity'         => 'required',
                    'unit_price'       => 'required',
                ]
            );

            if(!$rules){
                $response = [
                    'message' => $this->validator->getErrors()
                ];

                return $this->failValidationErrors($response);
            }

            
            $this->orderItemsModel->insert([
                'orderId'           => esc( $this->request->getVar('orderId')),
                'productId'         => esc($this->request->getVar('productId')),
                'quantity'          => esc($this->request->getVar('quantity')),
                'unit_price'        => esc($this->request->getVar('unit_price')),

            ]);

            

            $response = [
                'message' => 'success',
            ];


            return $this->respondCreated($response);
                
        }
        public function update($id = null){
            if($this->IsEmpty($id)){
                $response = [
                    'message' => 'Error: Order items not found',
                ];

                return $this->respond($response,404);

            }else{
                $rules = $this->validate(
                    [
                        'orderId'          => 'required',
                        'productId'        => 'required',
                        'quantity'         => 'required',
                        'unit_price'       => 'required',
                    ]
                );

                if(!$rules){
                    $response = [
                        'message' => $this->validator->getErrors()
                    ];

                    return $this->failValidationErrors($response);
                }
                
                
                $this->orderItemsModel->update($id, [
                    'orderId'             => esc( $this->request->getVar('orderId')),
                    'productId'           => esc($this->request->getVar('productId')),
                    'quantity'            => esc($this->request->getVar('quantity')),
                    'unit_price'          => esc($this->request->getVar('unit_price')),

                ]);

                $response = [
                    'message' => 'success',
                    
                ];


                return $this->respond($response, 200);
            }
        }
        public function del($id){
            
           $data = $this->orderItemsModel->find($id);

           if($this->IsEmpty($id)){
                $response = [
                    'message' => 'Error: order_items not found',
                ];

                return $this->respond($response,404);
           } else{
                $this->orderItemsModel->delete($id);

                $response = [
                'message' => 'Data deleted successfully',
                ];

                return $this->respondDeleted($response);
           }


            
        }

    }



