<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    class Admin extends CI_Model{ 

        function __construct(){
            parent::__construct();
        }

        public function addData($data, $type){
            return $this->db->insert($type, $data) ? true : false ;
        }

        public function crypt($string, $action){
            // you may change these values to your own
            $secret_key = 'my_simple_secret_key';
            $secret_iv = 'my_simple_secret_iv';
        
            $output = false;
            $encrypt_method = "AES-256-CBC";
            $key = hash( 'sha256', $secret_key );
            $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        
            if( $action == 'e' ) {
                $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
            }
            else if( $action == 'd' ){
                $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
            }
        
            return $output;
        }

        public function checkIfUserExists($phone, $type){
            $this->db->select('*');
            $query = $this->db->get_where($type, array('phone' => $phone));
            return $query->num_rows() > 0 ? true : false;
        }

        public function customerLogin($data){
            $this->db->select('id, name, email, phone, preference');
            $query = $this->db->get_where('customer', array('phone' => $data['phone'], 'password' => $data['password']))->row();
            return $query;
        }

        public function restaurantLogin($data){
            $this->db->select('id, name, email, phone');
            $query = $this->db->get_where('restaurant', array('phone' => $data['phone'], 'password' => $data['password']))->row();
            return $query;
        }

        public function getCustomerProfile($id){
            $this->db->select('name, email, phone, preference');
            $query = $this->db->get_where("customer", array('id' => $id));
            return $query->row();
        }

        public function getitems($resid){
            $this->db->select('*');
            $query = $this->db->get_where("item", array('restaurant_id' => $resid));
            return $query->result();
        }

        public function getAllRestaurants(){
            $this->db->select('id, name, phone');
            $query = $this->db->get("restaurant");
            return $query->result();
        }
    }