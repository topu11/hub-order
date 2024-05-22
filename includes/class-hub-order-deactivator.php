<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://touhidulislam.net
 * @since      1.0.0
 *
 * @package    Hub_Order
 * @subpackage Hub_Order/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Hub_Order
 * @subpackage Hub_Order/includes
 * @author     Touhidul <touhidulislam256@gmail.com>
 */
class Hub_Order_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$timestamp = wp_next_scheduled('hub_order_sync_cron_event');
		wp_unschedule_event($timestamp, 'hub_order_sync_cron_event');

		$slugs=['manage-single-order','manage-orders'];
		foreach($slugs as $key=>$value)
		{
			$page_slug = $value; 
			$page = get_page_by_path($page_slug);
			if ($page) {
				wp_delete_post($page->ID, true);
			}
		}
		

		

	}

}
