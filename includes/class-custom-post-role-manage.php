<?php
class Hub_order_custom_post_role_manage{
    public static function custom_role_post_create()
    {
            self::create_custom_post_type_order(); 
            self::add_customer_support_role();
            self::add_capabilities_customer_Support_role();
            self::add_custom_post_status();
    }
    public static function add_customer_support_role()
    {
        add_role(
            'customer_support',
            'Customer Support',
            array(
                'read' => true, // Allows this role to read posts
            )
        );
    }
    public static function add_capabilities_customer_Support_role()
    {
        $role = get_role('customer_support');
       
        // Add custom capabilities
        if ($role) {
            $role->add_cap('edit_hub_order_types');
            $role->add_cap('edit_others_hub_order_types');
            $role->add_cap('publish_hub_order_types');
            $role->add_cap('read_hub_order_types');
            $role->add_cap('delete_hub_order_types');
        }

        $role = get_role('administrator');
        // Add custom capabilities
        if ($role) {
            $role->add_cap('edit_hub_order_types');
            $role->add_cap('edit_others_hub_order_types');
            $role->add_cap('publish_hub_order_types');
            $role->add_cap('read_hub_order_types');
            $role->add_cap('delete_hub_order_types');
        }
    }
    public static function create_custom_post_type_order()
    {
        $labels = array(
            'name'                  => __('Orders', 'hub-order'),
            'singular_name'         => __('Order', 'hub-order'),
            'menu_name'             => __('Orders', 'hub-order'),
            'all_items'             => __('All Orders', 'hub-order'),
            'add_new'               => __('Add New', 'hub-order'),
            'add_new_item'          => __('Add New Order', 'hub-order'),
            'edit_item'             => __('Edit Order', 'hub-order'),
            'new_item'              => __('New Order', 'hub-order'),
            'view_item'             => __('View Order', 'hub-order'),
            'view_items'            => __('View Orders', 'hub-order'),
            'search_items'          => __('Search Orders', 'hub-order'),
            'not_found'             => __('No Orders found.', 'hub-order'),
            'not_found_in_trash'    => __('No Orders found in Trash.', 'hub-order'),
            'archives'              => __('Order Archives', 'hub-order'),
            'filter_items_list'     => __('Filter Orders list', 'hub-order'),
            'items_list_navigation' => __('Orders list navigation', 'hub-order'),
            'items_list'            => __('Orders list', 'hub-order')
         );
         
         // See all possible attributes in the PHPDoc of the function register_post_type
         $args = array(
             'label'                 => __('Orders', 'hub-order'),
             'labels'                => $labels, 
             'public'                => true,
             //'exclude_from_search'   => false,
             //'publicly_queryable'    => true,
             //'show_ui'               => true,
            // 'show_in_nav_menus'     => true,
             'show_in_menu'          => false,
            // 'show_in_admin_bar'     => true,
            // 'hierarchical'          => false,
             'supports'              => array('title', 'editor', 'author'), 
            // 'taxonomies'            => array('post_tag'),
            // 'has_archive'           => true, 
             'rewrite'               => array('slug' => 'orders'),
            // 'query_var'             => 'Order',
             'capability_type' => true,
            'capabilities' => array(
                'edit_post' => 'edit_hub_order_types',
                'edit_posts' => 'edit_hub_order_types',
                'edit_others_posts' => 'edit_hub_order_types',
                'publish_posts' => 'publish_hub_order_types',
                'read_post' => 'read_hub_order_types',
                'read_private_posts' => 'read_private_hub_order_types',
                'delete_post' => 'delete_hub_order_types',
            ),
         );
         
         register_post_type('hub_order_type', $args);
    }
    public static function add_custom_post_status()
    {
        $custom_post_statues=self::$cusstom_post_status;
        foreach($custom_post_statues as $key=>$value)
        {
            register_post_status( $value, array(
                'label'                     => _x( ''.$key.' ', 'post status label', 'hub-order' ),
                'public'                    => true,
                'label_count'               => _n_noop( ''.$key.'  s <span class="count">(%s)</span>', ''.$key.'  s <span class="count">(%s)</span>', 'plugin-domain' ),
                'post_type'                 => array( 'hub_order_type' ), // Define one or more post types the status can be applied to.
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'show_in_metabox_dropdown'  => true,
                'show_in_inline_dropdown'   => true,
                'dashicon'                  => 'dashicons-businessman',
            ) );
        }
       
    }
    public static $cusstom_post_status=[
        'hub-pending'=>'Pending Payment',
        'hub-processing'=>'Processing',
        'hub-on-hold'=>'On hold',
        'hub-completed'=>'Completed',
        'hub-cancelled'=>'Cancelled',
        'hub-refunded'=>'Refunded',
        'hub-failed'=>'Failed',
        'hub-checkout-draft'=>'Draft'
    ];
}    