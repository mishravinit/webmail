<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {
	
	public function index(){
		$result["message"] = "Nothing To Return";
		
		echo json_encode($result);
	}
	
	public function mails(){
		
	}
	
	
}