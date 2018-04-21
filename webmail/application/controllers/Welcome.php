<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	private $data;
	
	public function __construct()
	{
		parent::__construct();
	
		$this->headers();
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data['resources'] = $this->resources();
		$this->data['ng_app'] = 'defApp';
		$this->data['ng_ctrl'] = 'defCtrl';
	}
	
	public function headers() {
		header("Access-Control-Allow-Origin: *");
	}
	
	public function resources() {
		$resources = array(
				'js' => array('libs/jquery-2.2.3.min', 'libs/bootstrap.min', 'libs/select2.min','libs/angular.min','libs/ang-select2','libs/angular-cookies', 'helpers/general_helper'),
				'css' => array('libs/bootstrap.min', 'helpers/general','libs/select2.min','libs/select2-bootstrap')
		);
		return $resources;
	}
	
	public function addCSS($css_arr) {
		if(empty($css_arr)) {
			return;
		}
	
		if(empty($this->data['resources'])) {
			$this->data['resources'] = array();
		}
		if(empty($this->data['resources']['css'])) {
			$this->data['resources']['css'] = array();
		}
	
		if(is_array($css_arr)) {
			$this->data['resources']['css'] += $css_arr;
		} else {
			array_push($this->data['resources']['css'], $css_arr);
		}
	}
	
	public function addJs($js_arr) {
		if(empty($js_arr)) {
			return;
		}
	
		if(empty($this->data['resources'])) {
			$this->data['resources'] = array();
		}
		if(empty($this->data['resources']['js'])) {
			$this->data['resources']['js'] = array();
		}
	
		if(is_array($js_arr)) {
			$this->data['resources']['js'] += $js_arr;
		} else {
			array_push($this->data['resources']['js'], $js_arr);
		}
	}
	
	public function addExternalJs($js_path) {
		if(empty($js_path)) {
			return;
		}
	
		if(empty($this->data['resources'])) {
			$this->data['resources'] = array();
		}
		if(empty($this->data['resources']['ext_js'])) {
			$this->data['resources']['ext_js'] = array();
		}
	
		array_push($this->data['resources']['ext_js'], $js_path);
	}
	
	public function addExternalCSS($css_path) {
		if(empty($css_path)) {
			return;
		}
	
		if(empty($this->data['resources'])) {
			$this->data['resources'] = array();
		}
		if(empty($this->data['resources']['ext_css'])) {
			$this->data['resources']['ext_css'] = array();
		}
	
		array_push($this->data['resources']['ext_css'], $css_path);
	}

	public function index()
	{	
		$this->data['page_title'] = "webmail";
		$this->load->view('header', $this->data);
		$this->load->view('webmail');
		$this->load->view('footer', $this->data);
	}
}
