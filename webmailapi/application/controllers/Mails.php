<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mails extends CI_Controller {
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model('impls/mails_impl');
	}
	
	public function index(){
		$result["message"] = "Nothing To Return";
		
		echo json_encode($result);
	}
	
	public function mail(){
		$mailID =  $this->uri->segment(3);
		$method = $this->input->method();
		$request = $this->input->get();
		$request['mail_id'] = $mailID;
		
		switch($method){
			case 'post':
				$this->mails_impl->add_draft($request);
				break;
			case 'put':
				$this->mails_impl->update_draft($request);
				break;
			case "delete":
				$this->mails_impl->delete_mail($request);
			default :
				$response["messsage"] = "Invalid Request3"; 
				echo json_encode($response);
		}
	}
	
	public function query(){
		$mlType =  $this->uri->segment(3);
		$method = $this->input->method();
		$request = $this->input->get();
		
		
		switch($method){
			case "get":
				switch($mlType){
					case 'drafts':
					case 'sent':
					case 'inbox':
					case 'trash':
						$start = $request['ms'];
						$user_id = $request['uid'];
						$this->mails_impl->fetch_mail_list($user_id, $start, $mlType);
						break;
					case 'thread':
						$thread_id = $request['tid'];
						$mailType = $request['mt'];
						$user_id = $request['uid'];
						$this->mails_impl->fetch_mail_thread($thread_id, $mailType, $user_id);
						break;
					default:
						$response["messsage"] = "Invalid Request3";
						echo json_encode($response);
						die;
				}
			default:
				$response["messsage"] = "Invalid Request3";
				echo json_encode($response);
				die;
		}
	}
	
}