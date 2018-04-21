<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mails_Manager extends PM_Model {
	
	private $mails_table = "mails";
	private $recipients_table = "recipients";
	private $forwards_table = "forwards";
	private $replies_table = "replies";
	
	public function __construct()
	{
		parent::__construct();
	
	}
	
	public function fetch_latest_inbox_mails_for_user($userID, $start = 0, $limit =12){
		
		if(empty($userID)) return false;
		
		try {
			$this->db->select('ml.ID, ml.subject, ml.sender_id, ml.body,ml.attachments,ml.type,ml.sent_status,ml.timestamp,ml.thread_id, rt.read_status');
			$this->db->from("$this->mails_table ml");
			$this->db->join("$this->recipients_table rt", "ml.ID = rt.mail_id");
			$this->db->where("rt.to = $userID");
			$this->db->where("ml.type = 1");
			$this->db->where("rt.trashed = 0");
			$this->db->group_by("ml.thread_id");
			$this->db->order_by("ml.ID", "desc");
			$this->db->limit($limit, $start);
			
			$query = $this->db->get();
			if($query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				$error = $this->db->error();
				if(!empty($error) && !empty($error['message'])) {
					throw new Exception($error['message'], $error['code']);
				} else {
					return false;
				}
			}
		} catch(Exception $e) {
			return false;
		}
		
	}
	
	public function fetch_latest_trashed_mails_by_user($userID, $start = 0, $limit =12){
		
		if(empty($userID)) return false;
		
		try {
			$this->db->select('ml.*, rt.read_status');
			$this->db->from("$this->mails_table ml");
			$this->db->join("$this->recipients_table rt", "ml.ID = rt.mail_id");
			$this->db->where("rt.to = $userID");
			$this->db->where("rt.trashed = 1");
			$this->db->order_by("ml.ID", "desc");
			$this->db->limit($limit, $start);
			$this->db->group_by("ml.thread_id");
				
			$query = $this->db->get();
		
			if($query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				$error = $this->db->error();
				if(!empty($error) && !empty($error['message'])) {
					throw new Exception($error['message'], $error['code']);
				} else {
					return false;
				}
			}
		} catch(Exception $e) {
			return false;
		}
	}
	
	public function trash_inbox_mail($mail_id, $user_id){
		
		if(empty($mail_id) || empty($user_id)){
			return false;
		}
		
		$whr['mail_id'] = $mail_id;
		$whr['to'] = $user_id;
		$data['trashed'] = 1;
		return $this->update_data_multiple_where($this->recipients_table, $whr, $data);
	}
	
	public function trash_sender_mail($mail_id, $user_id){
		
		if(empty($mail_id) || empty($user_id)){
			return false;
		}
		
		$whr['ID'] = $mail_id;
		$whr['sender_id'] = $user_id;
		$data['trashed'] = 1;
		return $this->update_data_multiple_where($this->mails_table, $whr, $data);
	}
	
	public function fetch_latest_drafts_by_user($userID, $start = 0, $limit =12){
		
		if(empty($userID)) return false;
		
		/* $args['start'] = $start;
		$args['limit'] = $limit;
		$args['order_by_col'] = "ID";
		$args['order_by_order'] = "desc";
		$args['whr']['sent_status'] = "draft";
		$args['whr']['sender_id'] = $userID;
		return $this->select_multiple_all_from_table_by_multiple_coloumns($this->mails_table, $args); */
		
		try {
			$this->db->select('*');
			$this->db->from("$this->mails_table");
			$this->db->where("sent_status = 'draft'");
			$this->db->where("sender_id = $userID");
			$this->db->where("trashed = 0");
			$this->db->order_by("ID", "desc");
			$this->db->limit($limit, $start);
			$this->db->group_by("thread_id");
		
			$query = $this->db->get();
		
			if($query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				$error = $this->db->error();
				if(!empty($error) && !empty($error['message'])) {
					throw new Exception($error['message'], $error['code']);
				} else {
					return false;
				}
			}
		} catch(Exception $e) {
			return false;
		}
		
	}
	
	public function fetch_latest_sent_by_user($userID, $start = 0, $limit =12){
	
		if(empty($userID)) return false;
	
		/* $args['start'] = $start;
		$args['limit'] = $limit;
		$args['order_by_col'] = "ID";
		$args['order_by_order'] = "desc";
		$args['whr']['sent_status'] = "sent";
		$args['whr']['sender_id'] = $userID;
		return $this->select_multiple_all_from_table_by_multiple_coloumns($this->mails_table, $args); */
		
		try {
			$this->db->select('*');
			$this->db->from("$this->mails_table");
			$this->db->where("sent_status = 'sent'");
			$this->db->where("sender_id = $userID");
			$this->db->where("trashed = 0");
			$this->db->order_by("ID", "desc");
			$this->db->limit($limit, $start);
			$this->db->group_by("thread_id");
		
			$query = $this->db->get();
		
			if($query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				$error = $this->db->error();
				if(!empty($error) && !empty($error['message'])) {
					throw new Exception($error['message'], $error['code']);
				} else {
					return false;
				}
			}
		} catch(Exception $e) {
			return false;
		}
	
	}
	
	public function get_mail_thread($thread_id){
		
		if(empty($thread_id)){
			return false;
		}
		
		$args['order_by_col'] = "ID";
		$args['order_by_order'] = "desc";
		
		$mails = $this->select_multiple_all_from_table_by_coloumn_cond($this->mails_table, "thread_id", $thread_id,"=", $args);
		
		if(!empty($mails)){
			
			return $mails;
		}
		
		return false;
	}
	
	public function add_new_mail($args = array()){
		if(empty($args['sender_id'])) return false;
		
		if(empty($args['type'])) return false;
		
		if(!empty($args['sent_status']))
			$data['sent_status']  = $args['sent_status'];
		if(!empty($args['sender_id']))
			$data['sender_id']  = $args['sender_id'];
		if(!empty($args['subject']))
			$data['subject']  = $args['subject'];
		if(!empty($args['body']))
			$data['body']  = $args['body'];
		if(!empty($args['attachment_id']))
			$data['attachment_id']  = $args['attachment_id'];
		if(!empty($args['type']))
			$data['type']  = $args['type'];
		if(!empty($args['timestamp']))
			$data['timestamp']  = $args['timestamp'];
		if(!empty($args['thread_id']))
			$data['thread_id']  = $args['thread_id'];
		
		$mail_id = $this->insert_data($this->mails_table, $data);
		
		if(!empty($mail_id)){
			switch($args['type']){
				case '1': //new
					$udata['thread_id'] = $mail_id;
					$result = $this->update_data($this->mails_table, "ID", $mail_id, $udata);
					break;
				case '2': //forward
					$fdata['new_mail_id'] = $mail_id;
					$fdata['forwarded_mail_id'] = $args['forwarded_mail_id'];
					$result = $this->insert_data($this->forwards_table, $fdata);
					break;
				case '3': //reply
					$rdata['new_mail_id'] = $mail_id;
					$rdata['replied_mail_id'] = $args['replied_mail_id'];
					$result = $this->insert_data($this->replies_table, $rdata);
					break;
			}
		}else{
			return false;
		}
		
		$redata['mail_id'] = $mail_id;
		$redata['to'] = $args['to'];
		$redata['read_status'] = 0;
		$redata['trashed'] = 0;
		$this->update_mail_recipients($redata);
		
		return $mail_id;
		
	}
	
	public function update_mail_recipients($args = array()){
		if(empty($args)) return false;
		
		$this->delete_data($this->recipients_table, 'mail_id', $args['mail_id']);
		
		if(!empty($args['to'])){
			$toArr = $args['to'];
			//$date = date('Y-m-d H:i:s');
			
			//print_r($toArr);
			foreach($toArr as $to){
				$data['mail_id'] = $args['mail_id'];
				$data['to'] = $to;
				//$data['read_status'] = $args['read_status'];
				//$data['trashed'] = $args['trashed'];
				$this->insert_data($this->recipients_table, $data);
			}
		}
		
	}
	
	public function update_mail_by_id($args = array(), $whr=array()){
		if(empty($whr['mail_id']) || empty($args))
			return false;
		
		if(!empty($args['sent_status']))
			$data['sent_status']  = $args['sent_status'];
		if(!empty($args['sender_id']))
			$data['sender_id']  = $args['sender_id'];
		if(!empty($args['subject']))
			$data['subject']  = $args['subject'];
		if(!empty($args['body']))
			$data['body']  = $args['body'];
		if(!empty($args['attachment_id']))
			$data['attachment_id']  = $args['attachment_id'];
		if(!empty($args['type']))
			$data['type']  = $args['type'];
		if(!empty($args['timestamp']))
			$data['timestamp']  = $args['timestamp'];
		if(!empty($args['thread_id']))
			$data['thread_id']  = $args['thread_id'];
		
		$result = $this->update_data($this->mails_table, "ID", $whr['mail_id'], $data);
		
		if(!empty($result)){
			$redata['mail_id'] = $whr['mail_id'];
			$redata['to'] = $args['to'];
			$redata['read_status'] = 0;
			$redata['trashed'] = 0;
			$this->update_mail_recipients($redata);
				
			return $whr['mail_id'];
		}else{
			return false;
		}
	}
	
	public function mark_inbox_mails_read($mails, $user_id){
	
		if(empty($mails) || empty($user_id))
			return false;
	
		foreach($mails as $mail){
			$whr['mail_id'] = $mail->ID;
			$whr['to'] = $user_id;
			$data['read_status'] =1;
			
			$this->update_data_multiple_where($this->recipients_table, $whr, $data);
		}
	
	}
	
	public function mark_sender_mail_read($tid, $user_id){
		if(empty($tid) || empty($user_id))
			return false;
		
		$whr['thread_id'] = $tid;
		$whr['sender_id'] = $user_id;
		$data['read_status'] =1;
		
		$this->update_data_multiple_where($this->mails_table, $whr, $data);
	}
}