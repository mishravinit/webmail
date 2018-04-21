<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_Impl extends PM_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('managers/users_manager');
	}
	
	public function get_users_all(){
		
		return $this->users_manager->fetch_all_users();
		
	}
}