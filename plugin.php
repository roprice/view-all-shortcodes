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
				cursor:pointer;
			}
		</style>
		
        <div class="wrap">
        	<div id="icon-options-general" class="icon32"><br></div>
			<h1>Listing of shortcodes on this site</h1>
			<div class="section panel">
				<p>This page lists all the shortcode available to you. Click to copy<span id="clipboard-message"></span>.</p>

				
        	<h2>Shortcodes</h2>
        <?php
	        foreach($shortcode_tags as $code => $function)
	        {
	        	?>
	        		 <code title="Click to automatically copy to your clipboard." data-clipboard-text="[<?php echo $code; ?>]">[<?php echo $code; ?>]</code> 
	        	<?php
	        }
	    ?>

			
			</div>
		</div>
		
		
	    <!-- 2. Include library -->
		<script src="https://cdn.jsdelivr.net/npm/clipboard@1/dist/clipboard.min.js"></script>
		<!-- <style href="http://cdn.jsdelivr.net/g/primer"></style> -->
    

	    <!-- 3. Instantiate clipboard by passing a list of HTML elements -->
	    <script>
	    var btns = document.querySelectorAll('code');
	    var clipboard = new Clipboard(btns);

	    clipboard.on('success', function(e) {
	        console.log(e);
			document.getElementById("clipboard-message").innerHTML = '';
			document.getElementById("clipboard-message").innerHTML += "-- shortcode copied!";			
	    });

	    clipboard.on('error', function(e) {
	        console.log(e);
	    });
	    </script>
		
		
		<?php
	}
} // END class Available_Shortcodes_Listing
?>
