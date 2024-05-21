<?php
class Hub_order_manage_orders{
    
    public static function create_orders()
    {
        $validated=self::validate_input_request();
        if($validated->data['status']=="error")
        {
            return $validated;
        }
        $postdata = file_get_contents('php://input');
        $eventData = json_decode($postdata);
        $custom_post_statues_mapping=[
            'pending'=>'hub-pending',
            'processing'=>'hub-processing',
            'on-hold'=>'hub-on-hold',
            'completed'=>'hub-completed',
            'cancelled'=>'hub-cancelled',
            'refunded'=>'hub-refunded',
            'failed'=>'hub-failed',
            'checkout-draft'=>'hub-checkout-draft'
        ];
        //return $eventData->order_name;
        $args = array(
            'post_status' => $custom_post_statues_mapping[$eventData->order_status],
            'post_type'   => 'hub_order_type',     
            'meta_query' => array(
                array(
                    'key' => 'order_id',
                    'value' => $eventData->order_id,
                    'compare' => '='
                ),
                array(
                    'key' => 'customer_email',
                    'value' => $eventData->customer_email,
                    'compare' => '='
                ),
                array(
                    'key' => 'shop_secret',
                    'value' => $eventData->shop_secret,
                    'compare' => '='
                ),
                array(
                    'key' => 'shop_name',
                    'value' => $eventData->shop_name,
                    'compare' => '='
                ),
                array(
                    'key' => 'shop_url',
                    'value' => $eventData->shop_url,
                    'compare' => '='
                )
            )
        );
        
        $query = get_posts($args);
        
        if (!empty($query)) {
            return;
            wp_reset_postdata();
        
        }
       
        $wp_post_data=[
         'post_title'=>$eventData->order_name,
         'post_status'=>$custom_post_statues_mapping[$eventData->order_status],
         'post_type'=>'hub_order_type',
         'meta_input'=>[
            'shop_secret'=>$eventData->shop_secret,
            'shop_name'=>$eventData->shop_name,
            'shop_url'=>$eventData->shop_url,
            'order_id'=>$eventData->order_id,
            'order_date'=>$eventData->order_date,
            'customer_email'=>$eventData->customer_email,
            'customer_name'=>$eventData->customer_name,
            'customer_phone'=>$eventData->customer_phone,
            'order_note_content_admin'=>$eventData->order_note_content_admin,
            'shipping_date'=>$eventData->shipping_date,
            'note_by_customer'=>$eventData->note_by_customer,
            'billing_address'=>$eventData->billing_address,
            'shipping_address'=>$eventData->shipping_address,
            'products'=>$eventData->products,
            'order_status'=>$eventData->order_status,
            'currency_symbol'=>$eventData->currency_symbol,
            'currency_name'=>$eventData->currency_name,
         ]
        ];
      
        $result=wp_insert_post($wp_post_data);
         
        if( is_wp_error($result) )
        {
            error_log($result->get_error_message());
            
            $response = new WP_REST_Response([
                'status'=>'error',
                'message'=>"any internal server error",
                'is_internal_server_error'=>true 
           ]);
           $response->set_status(422);
        }else
        {
            $response = new WP_REST_Response([
                'status'=>'success',
                'message'=>"order stored succefully",
                'order_id'=>$result 
           ]);
           $response->set_status( 201 );
        } 
        

            // Set the status code for the response
        

            // Return the response
        return $response;
    }
    
    public static function validate_input_request()
    {
        $postdata = file_get_contents('php://input');
        $eventData = json_decode($postdata,true);
        $shop_ip=$_SERVER['REMOTE_ADDR'];
        
        $error_message=[];
        $rules=[[
               'variable'=>'shop_name',
               'message'=>'is required'
        ],[
            'variable'=>'shop_secret',
            'message'=>'is required'
        ],[
            'variable'=>'shop_url',
            'message'=>'is required'
        ]];
        
        
        foreach($rules as $key=>$value)
        {
            if(empty($eventData[$value['variable']]))
            {
                $error_message[]=str_replace('_',' ',$value['variable']).' '.$value['message'];
                
            }
        }
        global $wpdb;
        $hub_order_shop_registration = $wpdb->prefix . 'hub_order_shop_registration';
        $sql="SELECT * FROM $hub_order_shop_registration WHERE $hub_order_shop_registration.shop_ip=%s and $hub_order_shop_registration.shop_name=%s and $hub_order_shop_registration.shop_url=%s and $hub_order_shop_registration.is_active=1";
       
        $shop_ip_from_db = $wpdb->get_row($wpdb->prepare($sql, array($shop_ip, $eventData['shop_name'], $eventData['shop_url'])));
        //var_dump($shop_ip_from_db);
        
        if(empty($shop_ip_from_db))
        {
            $error_message[]='your Shop is not registered or inactive';
        }



        if(!empty($error_message) || empty($shop_ip_from_db))
        {
            
           // http_response_code(400);
           // echo json_encode();
            $response = new WP_REST_Response([
                'status'=>'error',
                 'message'=>$error_message 
            ]);

                // Set the status code for the response
            $response->set_status( 401 );

                // Return the response
            return $response;
        }else
        {
            $response = new WP_REST_Response([
                'status'=>'success',
                 'message'=>null, 
            ]);

                // Set the status code for the response
            $response->set_status( 200 );

                // Return the response
            return $response;
        }
        
        
    }
    
    public static function show_orders_table()
    {
        

        if ( ! is_user_logged_in() ) {
           
            return "401 Unauthorized";
        }
    
        // Check if the user is not an administrator or any other specific role
        if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'edit_hub_order_types' ) ) {
            // Set 401 Unauthorized status header
            return "401 Unauthorized ";
        }
       
        $cache_path=dirname( __FILE__ ).'/order-data-json-cache.js';
        $cache_data = file_get_contents($cache_path);
        if(empty($cache_data))
        {
            $order_data_database=self::order_table_gerenator();
            file_put_contents($cache_path,json_encode($order_data_database));
            $cache_data = file_get_contents($cache_path);
        }
      
        $order_data=json_decode($cache_data);
        if(!empty($order_data))
        {
            $meta_keys=['order_name','order_id','customer_email','customer_name','order_status','order_date','shipping_date'];
            $post_status_name_mapping=Hub_order_custom_post_role_manage::$cusstom_post_status;
            require_once ( dirname( __FILE__ ,2).'/admin/partials/order_manage/index.php');
        }
    }
    public static function order_table_gerenator()
    {
        global $wpdb;
        $wp_posts= $wpdb->prefix . 'posts';
        $wp_postmeta= $wpdb->prefix . 'postmeta';

        $meta_keys=['order_id','customer_email','customer_name','order_date','shipping_date','shop_name'];
        
        $query="SELECT posts.ID as post_order_id, posts.post_status as order_status, posts.post_title as order_name, ";
        
        foreach($meta_keys as $key=>$value)
        {
            if($key==count($meta_keys)-1)
            {

                $query .= " $value.meta_value as '$value' ";
            }else
            {
                $query .= " $value.meta_value as '$value', ";
            }
        }
        $query .="FROM  $wp_posts as posts ";
        foreach($meta_keys as $key=>$value)
        {

            $query .= " inner join $wp_postmeta as $value on posts.ID=$value.post_id and $value.meta_key='$value' ";
        }
        $query .=" order by post_order_id DESC";
        //return $query;
        return $wpdb->get_results($query);
        
    }
    public static function manage_oders_single()
    {
        if ( ! is_user_logged_in() ) {
           
            return "401 Unauthorized";
        }
    
        // Check if the user is not an administrator or any other specific role
        if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'edit_hub_order_types' ) ) {
            // Set 401 Unauthorized status header
            return "401 Unauthorized ";
        }
        $post_order_id=$_GET['post_order_id'];
        if(!isset($post_order_id) || empty($post_order_id))
        {
             return "401 Unauthorized";
        }
        global $wpdb;
        $posts = $wpdb->prefix . 'posts';
        $sql="SELECT * FROM $posts WHERE $posts.ID=%d and post_type='hub_order_type'";
       
        $single_post = $wpdb->get_row($wpdb->prepare($sql, array($post_order_id)));
        $cusstom_post_status=Hub_order_custom_post_role_manage::$cusstom_post_status;
        ob_start();
        require_once ( dirname( __FILE__ ,2).'/admin/partials/order_manage/single.php');
        return ob_get_clean();
    }
    public static function add_order_notes()
    {
      
        $shop_secret=get_post_meta($_POST['post_order_id'],'shop_secret',true);
        $shop_url=get_post_meta($_POST['post_order_id'],'shop_url',true);
        $shop_name=get_post_meta($_POST['post_order_id'],'shop_name',true);
        $order_id=get_post_meta($_POST['post_order_id'],'order_id',true);

        global $wpdb;
        $hub_order_shop_registration = $wpdb->prefix . 'hub_order_shop_registration';
        $sql="SELECT * FROM $hub_order_shop_registration WHERE $hub_order_shop_registration.shop_name=%s and $hub_order_shop_registration.shop_secret=%s and $hub_order_shop_registration.shop_url=%s and $hub_order_shop_registration.is_active=1";
       
        $is_hub_order_shop_registration= $wpdb->get_row($wpdb->prepare($sql, array($shop_name,$shop_secret, $shop_url)));
        
        if(!empty($is_hub_order_shop_registration))
        {
            $payload=[
                    'shop_secret'=>$shop_secret,
                    'shop_url'=> $shop_url,
                    'shop_name'=>$shop_name,
                    'order_id'=> $order_id,
                    'note'=> $_POST['notes'],

            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $shop_url.'/wp-json/order/store/v1/store/order/note',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);
            //var_dump($response);
           // exit;
            $status_code=curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($status_code == 201)
			{
                $order_note_content_admin=get_post_meta($_POST['post_order_id'],'order_note_content_admin',true);
                $order_note_content_admin_prev=json_decode($order_note_content_admin,true);
                array_push($order_note_content_admin_prev,$_POST['notes']);
        
              update_post_meta($_POST['post_order_id'],'order_note_content_admin',json_encode($order_note_content_admin_prev));
				return json_encode([
                    'success'=>'success',
                    'message'=>'Note added successfully'
                ]);
				
			}else
			{
				return  json_encode([
                    'success'=>'error',
                    'message'=>'Please see debug log for error'
                ]);
				error_log($response);
			}
        }else
        {
            return  json_encode([
                'success'=>'error',
                'message'=>'Shop is deactivated'
            ]);
            
        }
        
    }
    public static function change_order_status()
    {
        
        $shop_secret=get_post_meta($_POST['post_order_id'],'shop_secret',true);
        $shop_url=get_post_meta($_POST['post_order_id'],'shop_url',true);
        $shop_name=get_post_meta($_POST['post_order_id'],'shop_name',true);
        $order_id=get_post_meta($_POST['post_order_id'],'order_id',true);

        global $wpdb;
        $hub_order_shop_registration = $wpdb->prefix . 'hub_order_shop_registration';
        $sql="SELECT * FROM $hub_order_shop_registration WHERE $hub_order_shop_registration.shop_name=%s and $hub_order_shop_registration.shop_secret=%s and $hub_order_shop_registration.shop_url=%s and $hub_order_shop_registration.is_active=1";
       
        $is_hub_order_shop_registration= $wpdb->get_row($wpdb->prepare($sql, array($shop_name,$shop_secret, $shop_url)));
        
        if(!empty($is_hub_order_shop_registration))
        {
            $payload=[
                    'shop_secret'=>$shop_secret,
                    'shop_url'=> $shop_url,
                    'shop_name'=>$shop_name,
                    'order_id'=> $order_id,
                    'order_status'=> $_POST['order_status'],

            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $shop_url.'/wp-json/order/store/v1/change/order/status',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);
            //var_dump(json_decode($response)->order_note_content_admin);
            //exit;
            $status_code=curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($status_code == 201)
			{
               
                wp_update_post(array(
                    'ID'    => $_POST['post_order_id'],
                    'post_status'   =>$_POST['order_status']
                    ));

                update_post_meta($_POST['post_order_id'],'order_note_content_admin',json_decode($response)->order_note_content_admin);    

				return json_encode([
                    'success'=>'success',
                    'message'=>'Order Status Changed Successfully'
                ]);
				
			}else
			{
				return  json_encode([
                    'success'=>'error',
                    'message'=>'Please see debug log for error'
                ]);
				error_log($response);
			}
        }else
        {
            return  json_encode([
                'success'=>'error',
                'message'=>'Shop is deactivated'
            ]);
            
        }
    } 
}