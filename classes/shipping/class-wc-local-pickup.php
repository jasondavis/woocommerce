<?php
/**
 * Local Pickup Shipping Method
 * 
 * A simple shipping method allowing free pickup as a shipping method
 *
 * @class 		WC_Local_Pickup
 * @package		WooCommerce
 * @category	Shipping
 * @author		Patrick Garman (www.patrickgarman.com)
 */  

class WC_Local_Pickup extends WC_Shipping_Method {

	function __construct() { 
		$this->id 			= 'local-pickup';
		$this->method_title = __('Local Pickup', 'woocommerce');

		// Load the form fields.
		$this->init_form_fields();
		
		// Load the settings.
		$this->init_settings();
		
		// Define user set variables
		$this->enabled		= $this->settings['enabled'];
		$this->title		= $this->settings['title'];
		
		add_action('woocommerce_update_options_shipping_methods', array(&$this, 'process_admin_options'));
		
	} 

	 function calculate_shipping() {
		global $woocommerce;
		$_tax = new WC_Tax();
		
		$rate = array(
			'id' 		=> $this->id,
			'label' 	=> $this->title,
		);
		
		$this->add_rate($rate);  
	}
	
	function init_form_fields() {
    	global $woocommerce;
    	$this->form_fields = array(
			'enabled' => array(
				'title' 		=> __( 'Enable', 'woocommerce' ), 
				'type' 			=> 'checkbox', 
				'label' 		=> __( 'Enable local pickup', 'woocommerce' ), 
				'default' 		=> 'no'
			), 
			'title' => array(
				'title' 		=> __( 'Title', 'woocommerce' ), 
				'type' 			=> 'text', 
				'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ), 
				'default'		=> __( 'Local Pickup', 'woocommerce' )
			),
		);
	}

	function admin_options() {
		global $woocommerce; ?>
		<h3><?php echo $this->method_title; ?></h3>
		<p><?php _e('Local pickup is a simple method which allows the customer to pick up their order themselves.', 'woocommerce'); ?></p>
		<table class="form-table">
    		<?php $this->generate_settings_html(); ?>
    	</table> <?php
	}

}

function add_local_pickup_method($methods) { $methods[] = 'WC_Local_Pickup'; return $methods; }
add_filter('woocommerce_shipping_methods','add_local_pickup_method');