<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files_Manager extends PM_Model {
	
	private $attachments_table = "attachments";
	private $mails_table = "mails";
	
	public function __construct()
	{
		parent::__construct();
		$this->attachments_table = "attachments";
		$this->mails_table = "mails";
	}
	
	public function register_attachment($mail_id, $file_url){
		
		if(empty($mail_id) || empty($file_url)){
			return false;
		}
		
		$data['url'] = $file_url;
		$data['mail_id'] = $mail_id;
		
		$aid =  $this->insert_data($this->attachments_table, $data);
		if(!empty($aid)){
			$mdata['attachments'] = 1;
			$this->update_data($this->mails_table, "ID", $mail_id, $mdata);
			return $aid;
		}
		
		return false;
	}
	
	public function delete_all_attachments($mail_id){
		if(empty($mail_id)){
			return false;
		}
		
		$this->delete_data($this->attachments_table, "mail_id", $mail_id);
		
		$data['attachments'] = 0;
		$this->update_data($this->mails_table, "ID", $mail_id, $data);
	}
	
	public function get_mail_attachments($mail_id){
		if(empty($mail_id)){
			return false;
		}
		
		return $this->select_multiple_all_from_table_by_coloumn($this->attachments_table, "mail_id", $mail_id);
	}
	
}