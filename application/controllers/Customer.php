<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: access_token, Cache-Control');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE');
    }

    public function signup()
    {
        if (empty($_POST['phone'])) {
            $response = array(
                "status" => false,
                "message" => "Phone number missing"
            );
        } else {
            if ($this->admin->checkIfUserExists($_POST['phone'], 'customer')) {
                $response = array(
                    "status" => false,
                    "message" => "Customer already exists!"
                );
            } else {
                $_POST['password'] = $this->admin->crypt($_POST['password'], 'e');
                $data = $this->admin->addData($_POST, "customer");

                if ($data) {
                    $response = array(
                        "status" => true,
                        "phone" => $_POST['phone'],
                        "name" => $_POST['name'],
                        "message" => "Customer successfully registered!"
                    );
                } else {
                    $response = array(
                        "status" => false,
                        "message" => "Error occurred while adding"
                    );
                }
            }
        }
        echo json_encode($response);
    }

    public function login(){
        $_POST['password'] = $this->admin->crypt($_POST['password'], 'e');

        $data = $this->admin->customerLogin($_POST);  

        if($data){

            $newdata = array(
                'name'  =>  $data->name,
                'id'     => $data->id,
                'phone' => $data->phone
            );

            echo json_encode(['status' => true, 'data'=>$newdata, 'message' => 'Successful Login']);

        }
        else{
            echo json_encode(['status' => false, 'message' => 'Unsuccessful Login']);
        }
    }
}