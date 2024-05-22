<?php

/**
 * Fired during plugin activation
 *
 * @link       https://touhidulislam.net
 * @since      1.0.0
 *
 * @package    Hub_Order
 * @subpackage Hub_Order/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Hub_Order
 * @subpackage Hub_Order/includes
 * @author     Touhidul <touhidulislam256@gmail.com>
 */
class Hub_Order_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		Hub_order_create_table::create_hub_order_shop_registration_table();

		//$slugs=['manage-single-order','manage-oders'];
		$slugs_shortcode_mapping=[
			'manage-single-order'=>'manage_oders_single',
			'manage-orders'=>'manage_oders_functionalities'
		];

		$title_slug_mapping=[
			'manage-single-order'=>'Manage Single Order',
			'manage-orders'=>'Manage Orders'
		];
        
		foreach($slugs_shortcode_mapping as $key=>$value)
		{
			$the_slug = $key;
			$args = array(
			'name'           => $the_slug,
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1
			);
			$my_posts = get_posts($args);
			if(empty($my_posts))
			{
				$short_code=$value;

				$shortcode = '['.$value.']';

				error_log($shortcode);
				$wp_post_data=[
					'post_title'=>$title_slug_mapping[$key],
					'post_content'=>$shortcode,
					'post_status'=>'publish',
					'post_type'=>'page',
					'post_name' => $the_slug 
				];
				wp_insert_post($wp_post_data);
			}
		}

		if (!wp_next_scheduled('hub_order_sync_cron_event')) {
			wp_schedule_event(time(), 'every_two_minutes', 'hub_order_sync_cron_event');
		}
		

	}

}
