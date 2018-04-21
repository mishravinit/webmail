<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files extends CI_Controller {
	
	private $upload_dir; 
	
	public function __construct(){
		parent::__construct();
		$this->load->model('managers/files_manager');
		$this->load->model('managers/mails_manager');
		$this->upload_files_url = "http://localhost/uploadfiles/";
		$this->upload_dir = PRPATH."uploadfiles/";
	}
	
	public function index(){
		$response["message"] = "Invalid Request";
		echo json_encode($response);
		die;
	}
	
	
	private function upload_error(){
		$response['message'] = "Invalid Request";
		echo json_encode($response);
		die;
	}
	
	private function handle_attachment($args = array(),$mail_id){
		if(empty($args) || empty($mail_id)){
			$this->upload_error();
		}
		
		$this->files_manager->delete_all_attachments($mail_id);
		
		foreach($args as $file){
			
			if(isset($file['name'])) {
		
				$temp = $file["tmp_name"];
				$fname = $file["name"];
				$destination = $this->upload_dir.$fname;
				$upload = move_uploaded_file($temp,$destination);
				if(!empty($upload)){
					$file_url = $this->upload_files_url.$fname;
					$aid = $this->files_manager->register_attachment($mail_id, $file_url);
				}
				
			} else {
				$this->upload_error();
			}
		}
		
	}
	
	public function upload(){
		
		$method = $this->input->method();
		
		switch($method){
			case "post":
				$request = $_FILES;
				$mail_id = $_REQUEST['mi'];
				$this->handle_attachment($request,$mail_id);
				break;
			default:
				$this->upload_error();
		}
		
	}
}