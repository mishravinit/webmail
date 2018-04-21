<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_Manager extends PM_Model {
	
	private $users_table = "users";
	
	public function __construct()
	{
		parent::__construct();
	
	}
	
	public function fetch_all_users(){
		
		try {
			$this->db->select('ID, username, firstname, lastname, email');
			$query = $this->db->get($this->users_table);
				
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
			fclogToFile("Error:  fetch_all_users : Exception ".$e->getCode()." ".$e->getMessage());
			return false;
		}
	}
	
	private function fetch_user_by_key($key, $value){
		if(empty($key))
			return false;
		
		try {
			$this->db->select('ID, username, firstname, lastname, email');
			$this->db->where("$key = '$value'");
			$query = $this->db->get($this->users_table);
		
			if($query && $query->num_rows() > 0) {
				$result = $query -> result()[0];
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
			fclogToFile("Error:  fetch_user_by_key : Exception ".$e->getCode()." ".$e->getMessage());
			return false;
		}
	}
	
	public function fetch_user_by_username($username){
		if(empty($username))
			return false;
		
		return $this->fetch_user_by_key('username', $username);
	}
	
	public function fetch_user_by_ID($user_id){
		if(empty($user_id))
			return false;
	
		return $this->fetch_user_by_key('ID', $user_id);
	}
	
	public function auth_user($username, $password){
		if(empty($username) || empty($password)){
			return  false;
		}
		
		$whr['username'] = $username;
		$whr['password'] = $password;
		$exist = $this->check_record_exists($this->users_table, $whr);
		
		if(!empty($exist)){
			$user = $this->fetch_user_by_username($username);
		}
		
		if(!empty($user))
			return $user->ID;
		return false;
	}
	
	
}