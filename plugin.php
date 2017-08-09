<?php

/**
 * Plugin Name: All the Shortcodes
 * Plugin URI: 
 * Description: Creates an admin page (<a href="/wp-admin/options-general.php?page=view-all-shortcodes">Settings -> View all shortcodes</a>) that lets you view all shortcodes on your site. No configuration necessary.
 * Version: 1.4
 * Author: Rowan Price & Associates
 * Author URI: https://www.rowanprice.com
 * Text Domain: view-all-shortcodes
 * License:     GPL
*/
if(is_admin())
{
	// Create the function
	$shortcodes = new Available_Shortcodes_Listing();
}
/**
 * View all available shorttcodes on an admin page
 *
 * @author
 **/
class Available_Shortcodes_Listing
{
	public function __construct()
	{
		$this->Admin();
	}
	/**
	 * Create the admin area
	 */
	public function Admin(){
		add_action( 'admin_menu', array(&$this,'Admin_Menu') );
	}
	/**
	 * Function for the admin menu to create a menu item in the settings tree
	 */
	public function Admin_Menu(){
		add_submenu_page(
			'options-general.php',
			'View all shortcodes',
			'View all shortcodes',
			'manage_options',
			'view-all-shortcodes',
			array(&$this,'Display_Admin_Page'));
	}
	/**
	 * Display the admin page
	 */
	public function Display_Admin_Page(){
		global $shortcode_tags;
        ?>
		
		<style>
			code {
				padding: 5px 10px;
			    display: block;
			    float: left;
			    margin: 10px 20px;
			}
		</style>
		
        <div class="wrap">
        	<div id="icon-options-general" class="icon32"><br></div>
			<h1>Listing of shortcodes on this site</h1>
			<div class="section panel">
				<p>This page lists shortcodes available for you to use on this WordPress installation. Copy and paste any shortcode (including brackets) into a post, page, widget, template, etc., to see what it does! </p>
				
        	<h2>Shortcodes</h2>
        <?php
	        foreach($shortcode_tags as $code => $function)
	        {
	        	?>
	        		 <code>[<?php echo $code; ?>]</code> 
	        	<?php
	        }
	    ?>

			
			</div>
		</div>
		<?php
	}
} // END class Available_Shortcodes_Listing
?>
