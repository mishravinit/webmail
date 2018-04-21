<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	
	public function index(){
		
		$this->load->model('impls/users_impl');
		
		$users = $this->users_impl->get_users_all();
		
		if(!empty($users)){
			echo json_encode($users);
		}else{
			$response["message"] = "No users";
			echo json_encode($response);
		}
	}
	
}