<?php

class Hub_order_create_table{
    public static function create_hub_order_shop_registration_table()
    {
        global $wpdb;
         
        $table_name = $wpdb->prefix . 'hub_order_shop_registration';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `shop_name` VARCHAR(100) NULL, 
            `shop_url` VARCHAR(100) NULL, 
            `shop_ip` VARCHAR(100) NULL,
            `shop_secret` VARCHAR(255) NULL,
            `is_active` TINYINT NULL DEFAULT '0', 
            `created_at` TIMESTAMP NULL DEFAULT NULL, 
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_by` BIGINT NOT NULL DEFAULT '0',
             PRIMARY KEY (`id`)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}