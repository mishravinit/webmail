<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mails_Impl extends PM_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('managers/mails_manager');
	}
	
	public function add_draft($args){
		$response['success'] = false;
		$response['mail_id'] = false;
		$data['type'] = $args['cmmt'];
		$data['to'] = array();
		
		
		if(!empty($args['cmusers'])){
			$data['to'] = json_decode($args['cmusers']);
		}
		
		$data['body'] = $args['mb'];
		$data['subject'] = $args['ms'];
		
		if(!empty($args['tid']))
			$data['thread_id'] = $args['tid'];
		else
			$data['thread_id'] = 0;
		
		$data['sent_status'] = "draft";
		$data['timestamp'] = date('Y-m-d H:i:s');
		
		if(!empty($args['ms'])){
		//handle attachments
		}
		
		$data['sender_id'] = $args['si'];
		$data['sent_status'] = $args['ss'];
		
		if(!empty($args['frmid']))
			$data['forwarded_mail_id'] = $args['frmid'];
		if(!empty($args['rpmid']))
			$data['replied_mail_id'] = $args['rpmid'];
		
		$mail_id = $this->mails_manager->add_new_mail($data);
		
		if(!empty($mail_id)){
			$response['success'] = true; 
			$response['mail_id'] = $mail_id;
		}
		
		echo json_encode($response);
	}
	
	public function update_draft($args){
		$response['success'] = false;
		$response['mail_id'] = false;
		
		if(empty($args['mail_id'])){
			echo json_encode($response);
			die;
		}
		
		$data['type'] = $args['cmmt'];
		$data['to'] = array();
		
		if(!empty($args['cmusers'])){
				$data['to'] = json_decode($args['cmusers']);
		}
		
		$data['body'] = $args['mb'];
		$data['subject'] = $args['ms'];
		$data['sent_status'] = $args['ss'];
		if(!empty($args['tid']))
			$data['thread_id'] = $args['tid'];
		else
			$data['thread_id'] = 0;
		
		$whr['mail_id'] = $args['mail_id'];
		$uresult = $this->mails_manager->update_mail_by_id($data, $whr);
		
		if(!empty($uresult)){
			$response['mail_id'] = $args['mail_id'];
			$response['success'] = true;
			echo json_encode($response);
		}
	}
	
	public function delete_mail($args = array()){
		
		$response['success'] = false;
		
		if(empty($args)){
			$response['message'] = "Invalid Request";
			echo json_encode($response);
			die;
		}
		
		$mail_id = $args['mail_id'];
		$mail_type = $args['mt'];
		$user_id = $args['ui'];
		
		if(empty($mail_id) || empty($user_id)){
			$response['message'] = "Invalid Request";
			echo json_encode($response);
			die;
		}
		
		switch($mail_type){
			case 'inbox':
				$result = $this->mails_manager->trash_inbox_mail($mail_id,$user_id);
				break;
			case 'draft':
			case 'sent':
				$result = $this->mails_manager->trash_sender_mail($mail_id,$user_id);
				break;
			default:
				$response['message'] = "Invalid Request";
				echo json_encode($response);
				die;
		}
		
		if(!empty($result)){
			$response['success'] = true;
		}
		
		echo json_encode($response);
		die;
	}
	
	public function fetch_mail_list($user_id, $start = 0, $type){
		
		$response = array("success" => false, "mails" => null);
		if(empty($user_id)){
			echo json_decode($response);
			die;
		}
		
		$limit = 12;
		
		switch($type){
			case 'drafts':
				$mails = $this->mails_manager->fetch_latest_drafts_by_user($user_id, $start, $limit);
				break;
			case 'sent':
				$mails = $this->mails_manager->fetch_latest_sent_by_user($user_id, $start, $limit);
				break;
			case 'inbox':
				$mails = $this->mails_manager->fetch_latest_inbox_mails_for_user($user_id, $start, $limit);
				break;
			case 'trash':
				$mails = $this->mails_manager->fetch_latest_trashed_mails_by_user($user_id, $start, $limit);
				break;
			default:
				echo json_encode($response);
				die;
		}
		
		$mails = $this->prepare_maillist_response($mails, $type);
		
		if(!empty($mails)){
			$response["success"] = true;
			$response["mails"] = $mails;
		}
		
		echo json_encode($response);
		die;
	}
	
	
	
	private function prepare_maillist_response($mails = array(), $type){
		if(empty($mails))
			return false;
		
		$this->load->model('managers/users_manager');
		$this->load->model('managers/files_manager');
		foreach($mails as $mail){
			
			$sender = $this->users_manager->fetch_user_by_ID($mail->sender_id);
			
			$mail->sender_name = $sender->firstname;
			$mail->sender_email = $sender->email;
			$mail->sender_id = $sender->ID;
			
			
			if(!empty($mail->attachments) ){
				$attachments_data = $this->files_manager->get_mail_attachments($mail->ID);
				$mail->attached = array();
				if(!empty($attachments_data)){
					//print_r($attachments_data);
					foreach($attachments_data as $data){
						array_push($mail->attached, $data->url);
					}
				}
				
			}
			
		}
	
		return $mails;
	}
	
	
	
	private function mark_mail_thread_read($tid,$mails,$mailtype,$user_id){

		if(empty($tid) || empty($mailtype) || empty($user_id)){
			return false;
		}
		
		switch($mailtype){
			case 'inbox':
				if(empty($mails)){
					$mails = $this->mails_manager->get_mail_thread($tid);
				}
				$this->mails_manager->mark_inbox_mails_read($mails, $user_id);
				break;
			case 'drafts':
			case 'sent':
				$this->mails_manager->mark_sender_mail_read($tid, $user_id);
				break;
		}
	}
	
	public function fetch_mail_thread($tid, $mailtype,$user_id){
		$response = array("success" => false, "mailthread" => null);
		
		if(empty($tid) || empty($mailtype)){
			echo json_decode($response);
			die;
		}
		
		$mails = $this->mails_manager->get_mail_thread($tid);
		
		$mails = $this->prepare_maillist_response($mails,$mailtype);
		
		if(!empty($mails)){
			$this->mark_mail_thread_read($tid,$mails,$mailtype,$user_id);
			$response["success"] = true;
			$response["mailthread"] = $mails;
		}
		
		echo json_encode($response);
		die;
		
	}
	
	
}