

	</div> <!-- #container_div -->
	
	<!-- Start Scripts -->
	<?php if(!empty($resources['ext_js'] )) { ?>
		<?php foreach($resources['ext_js'] as $js) {?>
		<script type="text/javascript" src="<?php echo $js; ?>" ></script>
		<?php } ?>
	<?php } ?>
	
	<?php foreach($resources['js'] as $js) {?>
	<script type="text/javascript" src="<?php echo base_url('assets/js/'.$js.'.js');?>" ></script>
	<?php } ?>
	<!-- End Scripts -->
	
	
</body>

</html>