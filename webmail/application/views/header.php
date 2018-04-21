<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<!--[if lte IE 8 ]><html lang="en" class="ie ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="ie"><![endif]-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge" /><![endif]-->
	
	<title><?php echo $page_title; ?></title>

	<?php foreach($resources['css'] as $css) {?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/'.$css.'.css');?>">
	<?php } ?>
	
</head>
<?php
if(empty($ng_app)) {
	$ng_app = "defApp"; 
	$ng_ctrl = "defCtrl";
}?>
<body ng-app="<?php echo $ng_app?>" ng-controller="<?php echo $ng_ctrl?>" ng-cloak="">
	
	<div id="page_container_div" class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
	
		
	