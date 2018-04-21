<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index(){
		$response['success'] = false;
		$response['uid'] = false;
		$method = $this->input->method();
		//echo $method;
		if($method != 'get')
		{
			$response['success'] = false;
			$repsonse['message'] = "Invalid Request";
			echo json_encode($response);
			die;
		}	
		
		$request = $this->input->get();
		
		$username = $request['u'];
		$password = $request['p'];
		$this->load->model("managers/users_manager");
		
		$result = $this->users_manager->auth_user($username, $password);
		
		if(!empty($result)){
			$response['success'] = true;
			$response['uid'] = $result;
		}
		
		echo json_encode($response);
		die;
	}
	
}