<?php
/**
 * WooCommerce coupons
 * 
 * The WooCommerce coupons class gets coupon data from storage
 *
 * @class 		woocommerce_coupon
 * @package		WooCommerce
 * @category	Class
 * @author		WooThemes
 */
class woocommerce_coupon {
	
	var $code;
	var $id;
	var $type;
	var $amount;
	var $individual_use;
	var $product_ids;
	var $usage_limit;
	var $usage_count;
	
	/** get coupon with $code */
	function woocommerce_coupon( $code ) {
		
		$this->code = $code;
		
		$coupon = get_page_by_title( $this->code, 'OBJECT', 'shop_coupon' );
		
		if ($coupon && $coupon->post_status == 'publish') :
			
			$this->id				= $coupon->ID;
			$this->type 			= get_post_meta($coupon->ID, 'discount_type', true);
			$this->amount 			= get_post_meta($coupon->ID, 'coupon_amount', true);
			$this->individual_use 	= get_post_meta($coupon->ID, 'individual_use', true);
			$this->product_ids 		= array_map('trim', explode(',', get_post_meta($coupon->ID, 'product_ids', true)));
			$this->usage_limit 		= get_post_meta($coupon->ID, 'usage_limit', true);
			$this->usage_count 		= (int) get_post_meta($coupon->ID, 'usage_count', true);
			
			if (!$this->amount) return false;
			
			return true;
			
		endif;
		
		return false;
	}
	
	/** Increase usage count */
	function inc_usage_count() {
		$this->usage_count++;
		update_post_meta($this->id, 'usage_count', $this->usage_count);
	}
	
	/** Check coupon is valid */
	function is_valid($code) {
		
		global $woocommerce;
		
		if ($this->id) :
		
			if (sizeof( $this->product_ids )>0) :
				$valid = false;
				if (sizeof($woocommerce->cart->cart_contents)>0) : foreach ($woocommerce->cart->cart_contents as $item_id => $values) :
					if (in_array($item_id, $this->product_ids)) :
						$valid = true;
					endif;
				endforeach; endif;
				if (!$valid) return false;
			endif;
			
			if ($this->usage_limit>0) :
				if ($this->usage_count>$this->usage_limit) :
					return false;
				endif;
			endif;
			
			return true;
		
		endif;
		
		return false;
	}
}
