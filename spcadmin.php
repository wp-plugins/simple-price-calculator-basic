<?php

class SimplePriceCalcAdmin {

//Admin Panel Functions

	public function __construct() {
	
		add_action('init', array($this, 'simple_admin_panel'));
		add_action( 'admin_init', array($this,'admin_panel_meta' ));
		add_filter( 'manage_edit-simple_price_calc_columns', array( $this, 'admin_table_columns' ) );
		add_action('manage_simple_price_calc_posts_custom_column', array( $this, 'admin_table_columns_data'), 10, 2 );
		add_action('edit_form_after_title', array($this,'default_form_content'));
		add_filter('post_updated_messages', array($this,'admin_updated_messages' ));
		
	}

  function simple_admin_panel() {
		register_post_type('simple_price_calc', array(
				'labels' => array(
				'name' => 'Simple Price Calculator Forms' ,
				'singular_name' =>  'Simple Price Calculator Form',
				'add_new_item' => 'Add New Form' ,
				'edit_item' => 'Edit Form'
				),
				'public' => false,
				'rewrite' => false,
				'has_archive' => true,
				'menu_position' => 100,
				'menu_icon' => 'dashicons-media-text',
				'show_ui' => true
			)
		);
	}
	
	function admin_panel_meta() {
		add_meta_box( 'spc_formtag_meta_box', 'Form Tag Generator', array($this,'admin_formtag_box'), 'simple_price_calc', 'side', 'default');
		add_meta_box( 'spc_mail_meta_box', 'Optional Price Form Settings', array($this,'admin_form_settings'), 'simple_price_calc', 'normal', 'default');			
	}
	
	function admin_formtag_box( ) {
		
		include('formgencode.php'); 
	}
	
	function admin_form_settings($post) {
		?>
		<h3>Functions below available in premium version.</h3> <a href="http://shop.premiumbizthemes.com/?download=simple-price-calculator-wordpress-version"> Click here to download premium version </a> <br /> <br />
		Add e-mail functionality to form?
		<input type="checkbox" name="spc_email_func" value="1" disabled/>
		E-mail to:
		<input type="email" name="spc_email" value="youremail@email.com" disabled/> <br />
		Form Currency Symbol:
		<select name="spc_currency_setting" disabled>
			<option value="dollar">$ </option>
			<option value="euro">&euro; </option>
		</select> <br />
		Total Box Location: 
		<select name="spc_totalbox_setting" disabled>
			<option value="right"> Right Side of Screen</option>
			<option value="below"> Below Form </option>
		</select> <br />
		
		Total Label Text: <input type="text" name="spc_totallabel_setting" value="" disabled/>  
	   Details Label Text: <input type="text" name="spc_detailslabel_setting" value="" disabled/>
		
	<?php
	}
	
	function admin_table_columns($columns) {
		$columns['shortcode'] = 'Shortcode';
		$columns['email'] = 'Email';
		
		return $columns;
	}
	
	function admin_table_columns_data($column,$post_id) {
	
		switch($column){
			case 'shortcode':			
			if($post_id)			
			echo "[spc-form id=" . $post_id . "]";
			break;		
			
			case 'email':			
			$savedemail='No e-mail specified';
			echo $savedemail;
			break;		

			default:
			echo $column . $post_id;			
		}
	}
	
	
	//Displays default form content if post is empty
	
	function default_form_content() {
		global $post;
		if ($post->post_type == 'simple_price_calc'  && $post->post_content == '') {
			
			$sampformcontent='
			<h2> Sample Heading </h2>
			<select>
				<option> Choose Option Type  </option> 
				<option> Basic Type </option>
				<option> Medium Type </option>
				<option> Advanced Type </option>
			</select> 
			
			<br />			
            <br />
			
			<h4> Sample Checkbox Settings </h4>
             
			<input type="checkbox" value="10"> Sample Checkbox 1
			<input type="checkbox" value="12"> Sample Checkbox 2
			<input type="checkbox" value="14"> Sample Checkbox 3			
			
			<br />
		    <br />
			
			<h4> Sample Radio Settings </h4>
			<input type="radio" name="css" value="0"> None <br />
			<input type="radio" name="css" value="5"> Radio Setting 1  <br />
			<input type="radio" name="css" value="10"> Radio Setting 2  <br />			
			
             <br />';
			
			$post->post_content = $sampformcontent;		
		}
    
	}		

	function admin_updated_messages( $messages ) {
		$messages['simple_price_calc'] = array(
			1  => sprintf(__( 'Form updated. <a href="%s">View Shortcode</a>' ), esc_url(admin_url('edit.php?post_type=simple_price_calc') ) ) ,
			6  => sprintf(__( 'Form published. <a href="%s">View Shortcode</a>' ), esc_url(admin_url('edit.php?post_type=simple_price_calc') ) ),
			7  => __ ('Form saved.' ),
			10  => __ ('Form draft updated.' )
		);
		return $messages;
	}
	
}	

$simplepricecalcadmin= new SimplePriceCalcAdmin();