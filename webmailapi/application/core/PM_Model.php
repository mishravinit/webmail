<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PM_Model extends CI_Model {


	public function __construct()
	{
		parent::__construct();

	}
	

	public function insert_data($table, $data) {
		
		if(empty($table) || empty($data)) {
			return false;
		}
		try {
				
			$insert_result = $this->db->insert($table, $data);
			if(!empty($insert_result)) {
				
				$insert_result = $this->db->insert_id();
				
			} 
			$error = $this->db->error();
			if(!empty($error) && !empty($error['message'])) {
				throw new Exception($error['message'], $error['code']);
			}
			
			return $insert_result;
			
		} catch(Exception $e) {
			return false;
		}
	}

	public function select_single_all_from_table_by_coloumn($table_name, $column_name, $column_value){
		if(empty($column_name) || empty($column_value) ||  empty($table_name)) {
			return false;
		}
		
		try {
			$this->db->select('*');
			$this->db->where("$column_name = '$column_value'");
			$query = $this->db->get($table_name);
			
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
			return false;
		}
	}
	
	public function select_single_all_from_table_by_multiple_coloumn($table_name, $args){
			if(empty($args) ||  empty($table_name)) {
			return false;
		}
		
	
		try {
			$this->db->select('*');
			foreach($args['whr'] as $column_name=>$column_value){
				$this->db->where("$column_name = '$column_value'");
			}
			
			$limit = 1;
			$start = 0;
			$this->db->limit($limit, $start);
				
			$query = $this->db->get($table_name);
			
			if( $query && $query->num_rows() > 0) {
				$result = $query -> result()[0];
				return $result;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}
	}
	
	
	public function select_multiple_all_from_table_by_coloumn($table_name, $column_name, $column_value, $args = array()){
		if(empty($column_name) || empty($column_value) ||  empty($table_name)) {
			return false;
		}
	
		try {
			$this->db->select('*');
			$this->db->where("$column_name = '$column_value'");
			
			if(!empty($args['limit'])){
				$limit = $args['limit'];
				$start = empty($args['start'])? 0: $args['start'];
				$this->db->limit($limit, $start);
			}
			
			if(!empty($args['order_by_col']) && !empty($args['order_by_order'])){		
				$order_by_col = $args['order_by_col'];
				$order_by_order = $args['order_by_order'];
				$this->db->order_by($order_by_col, $order_by_order);
			}	
			
			$query = $this->db->get($table_name);
			
			if( $query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}

	}
	
	
	public function select_multiple_all_from_table_by_coloumn_like($table_name, $column_name, $column_value, $args){
		if(empty($column_name) || empty($column_value) ||  empty($table_name)) {
			return false;
		}
		
		if(empty($args['str'])){
			return false;
		}
	
		try {
			$this->db->select('*');
			$this->db->where("$column_name like '%$column_value%'");
			$limit = $args['limit'];
			if(!empty($limit)){
				$start = $args['start'];
				if(empty($start)){
					$start = 0;
				}
				$this->db->limit($limit, $start);
			}
				
			$order_by_col = $args['order_by_col'];
			$order_by_order = $args['order_by_order'];
			if(!empty($order_by_col) && !empty($order_by_order)){
				$this->db->order_by($order_by_col, $order_by_order);
			}
				
			$query = $this->db->get($table_name);
				
			if( $query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}
	
	}
	
	public function select_multiple_all_from_table_by_coloumn_cond($table_name, $column_name, $column_value, $cond,$args = array()){
		if(empty($column_name) || empty($column_value) ||  empty($table_name)||  empty($cond)) {
			return false;
		}
	
		try {
			$this->db->select('*');
			$this->db->where("$column_name $cond '$column_value'");
				
			if(!empty($args['limit'])){
				$limit = $args['limit'];
				$start = empty($args['start'])? 0: $args['start'];
				$this->db->limit($limit, $start);
			}
				
			if(!empty($args['order_by_col']) && !empty($args['order_by_order'])){
				$order_by_col = $args['order_by_col'];
				$order_by_order = $args['order_by_order'];
				$this->db->order_by($order_by_col, $order_by_order);
			}
				
			$query = $this->db->get($table_name);
				
			if( $query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}
	
	}
	
	
	public function select_multiple_all_from_table_by_no_coloumn($table_name, $args){
		if(empty($table_name)) {
			return false;
		}
	
		try {
			$this->db->select('*');
			$limit = $args['limit'];
			if(!empty($limit)){
				$start = $args['start'];
				if(empty($start)){
					$start = 0;
				}
				$this->db->limit($limit, $start);
			}
			
			$order_by_col = $args['order_by_col'];
			$order_by_order = $args['order_by_order'];
			if(!empty($order_by_col) && !empty($order_by_order)){				
				$this->db->order_by($order_by_col, $order_by_order);
			}	
			
			$query = $this->db->get($table_name);
	
			if( $query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}

	}
	
	//fetch some columns for multiple records without where condition on any colum
	public function select_multiple_some_from_table_by_no_coloumn($table_name, $columns=array(), $args){
		if(empty($table_name)) {
			return false;
		}
	
		try {
			if(!empty($columns)){
				foreach($columns as $column){
					$this->db->select($column);
				}
			}			
			$limit = $args['limit'];
			if(!empty($limit)){
				$start = $args['start'];
				if(empty($start)){
					$start = 0;
				}
				$this->db->limit($limit, $start);
			}
				
			$order_by_col = $args['order_by_col'];
			$order_by_order = $args['order_by_order'];
			if(!empty($order_by_col) && !empty($order_by_order)){
				$this->db->order_by($order_by_col, $order_by_order);
			}
				
			$query = $this->db->get($table_name);
			
			//echo $this->db->last_query();
	
			if( $query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}
	
	}
	
	
	//fetch some columns for single records without where condition on any colum
	public function select_single_some_from_table_by_no_coloumn($table_name, $columns=array(), $args){
		if(empty($table_name)) {
			return false;
		}
	
		try {
			if(!empty($columns)){
				foreach($columns as $column){
					$this->db->select($column);
				}
			}
			$this->db->limit(1, 0);
	
			$order_by_col = $args['order_by_col'];
			$order_by_order = $args['order_by_order'];
			if(!empty($order_by_col) && !empty($order_by_order)){
				$this->db->order_by($order_by_col, $order_by_order);
			}
	
			$query = $this->db->get($table_name);
	
			if( $query && $query->num_rows() > 0) {
				$result = $query -> result()[0];
				return $result;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}
	
	}
	
	public function select_multiple_some_from_table_by_multiple_coloumns($table_name, $columns, $args){
	
		if(empty($args) ||  empty($table_name) ||  empty($columns)) {
			return false;
		}
	
	
		try {
			if(!empty($columns)){
				foreach($columns as $column){
					$this->db->select($column);
				}
			};
			foreach($args['whr'] as $column_name=>$column_value){
				$this->db->where("$column_name = '$column_value'");
			}
				
			if(!empty($args['limit'])){
				$limit = $args['limit'];
				$start = empty($args['start'])? 0: $args['start'];
				$this->db->limit($limit, $start);
			}
			
			if(!empty($args['order_by_col']) && !empty($args['order_by_order'])){		
				$order_by_col = $args['order_by_col'];
				$order_by_order = $args['order_by_order'];
				$this->db->order_by($order_by_col, $order_by_order);
			}
	
			$query = $this->db->get($table_name);
				
			if( $query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}
	
	}
	
	public function select_multiple_all_from_table_by_multiple_coloumns($table_name, $args){
		
		if(empty($args) ||  empty($table_name)) {
			return false;
		}
		
	
		try {
			$this->db->select('*');
			foreach($args['whr'] as $column_name=>$column_value){
				$this->db->where("$column_name = '$column_value'");
			}
			
			$limit = $args['limit'];
			if(!empty($limit)){
				$start = $args['start'];
				if(empty($start)){
					$start = 0;
				}
				$this->db->limit($limit, $start);
			}
			
			if(!empty($args['order_by_col']) && !empty($args['order_by_order'])){
				$order_by_col = $args['order_by_col'];
				$order_by_order = $args['order_by_order'];
				$this->db->order_by($order_by_col, $order_by_order);
			}
				
			$query = $this->db->get($table_name);
			
			if( $query && $query->num_rows() > 0) {
				$result = $query -> result();
				return $result;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}
	
	}
	
	public function delete_data($table_name, $where_key_col, $where_key_val) {
	
		if(empty($table_name) || empty($where_key_col) || empty($where_key_val)) {
			return false;
		}
		try {
				
			$this->db->where("$where_key_col = '$where_key_val'");
			$this->db->delete($table_name); 
	
			$error = $this->db->error();
			if(!empty($error) && !empty($error['message'])) {
				throw new Exception($error['message'], $error['code']);
			}
	
			return true;
	
		} catch(Exception $e) {
			return false;
		}
	}
	
	public function delete_data_where_multiple($table_name, $whr=array()) {
	
		if(empty($table_name) || empty($whr) || !is_array($whr)) {
			return false;
		}
		try {
			
			foreach($whr as $key => $value){
				$this->db->where("$key = '$value'");
			}
			$this->db->delete($table_name);
	
			$error = $this->db->error();
			if(!empty($error) && !empty($error['message'])) {
				throw new Exception($error['message'], $error['code']);
			}
	
			return true;
	
		} catch(Exception $e) {
			return false;
		}
	}
	
	public function update_data($table_name, $where_key_col, $where_key_val, $data) {
		
		if(empty($table_name) || empty($where_key_col) || empty($where_key_val) || empty($data)) {
			return false;
		}
		try {
			
			$this->db->where("$where_key_col = '$where_key_val'");
			$result = $this->db->update($table_name, $data); 
				
			$error = $this->db->error();
			if(!empty($error) && !empty($error['message'])) {
				throw new Exception($error['message'], $error['code']);
			}
				
			return $result;
				
		} catch(Exception $e) {
			return false;
		}
	}
	
	public function update_data_multiple_where($table_name, $whr, $data) {
	
		if(empty($table_name) || empty($whr) || empty($data)) {
			return false;
		}
		try {
				
			foreach($whr as $col_name => $col_val){
				$this->db->where("$col_name = '$col_val'");
			}	
			$this->db->update($table_name, $data);
	
			$error = $this->db->error();
			if(!empty($error) && !empty($error['message'])) {
				throw new Exception($error['message'], $error['code']);
			}
	
			return true;
	
		} catch(Exception $e) {
			return false;
		}
	}
	
	public function check_record_exists($table_name, $whr=array()) {
		
		if(empty($table_name) || empty($whr)) {
			return false;
		}
		
			
		try {
			$this->db->select('1');
			foreach($whr as $col_name => $col_val){
				$this->db->where("$col_name = '$col_val'");
			}			
			$query = $this->db->get($table_name);
			
			$error = $this->db->error();
			if(!empty($error) && !empty($error['message'])) {
				throw new Exception($error['message'], $error['code']);
			}
			if($query && $query->num_rows() > 0) {
				return true;
			}
			
			return false;
			
		
		} catch(Exception $e) {
			return false;
		}
		
	}
	

	
}