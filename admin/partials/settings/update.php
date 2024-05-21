<?php

if(!isset($_GET['id']) || empty($_GET['id']))
{
 echo '<div class="wp-die-message">Sorry, you are not allowed to access this page.</div>';
}
$id=filter_var( $_GET['id'], FILTER_SANITIZE_NUMBER_INT);
if(empty($id))
{
    echo '<div class="wp-die-message">Sorry, you are not allowed to access this page.</div>';   
}
if(isset($_POST['submit_btn']))
{
    //var_dump($_POST);
    $fields=['shop_name','shop_url','shop_secret','shop_ip'];
    foreach($fields as $keys=>$value)
    {
        if(empty($_POST[$value]))
        {
            return;
        }
    }
    global $wpdb;
    $wp_hub_order_shop_registration=$wpdb->prefix . 'hub_order_shop_registration';
    $data=[
        'shop_name'=>$_POST["shop_name"],
        'shop_url'=>$_POST["shop_url"],
        'shop_secret'=>$_POST["shop_secret"],
        'is_active'=>$_POST["is_active"],
        'shop_ip'=>$_POST["shop_ip"],
        'created_at'=>date('Y-m-d H:i:s'),
        'updated_at'=>date('Y-m-d H:i:s'),
        'updated_by'=>get_current_user_id()

    ];
    $wpdb->update($wp_hub_order_shop_registration, $data,['id'=>$id]);
    echo '<script>alert("Shop updated successfully")</script>';
}

global $wpdb;
$hub_order_shop_registration = $wpdb->prefix . 'hub_order_shop_registration';
$sql="SELECT * FROM $hub_order_shop_registration WHERE $hub_order_shop_registration.id=%d";
$shop_row = $wpdb->get_row($wpdb->prepare($sql, array($id)));

?>
<style>
    :root {
        --white-color: #ffffff;
        --primary-color: #91d3ee;
        --border-color: #8c8f94;
        --text-color: #3c434a;
        --bg-secondary-color: #c3e1ff;
    }

    .btn-back {
        border: 2px solid var(--primary-color);
        color: var(--text-color);
        padding: 7px 15px;
        border-radius: 6px;
        font-size: 15px;
        cursor: pointer;
        font-weight: 500;
    }

    input[type="number"] {
        max-width: 100px;
        margin: 0;
    }
</style>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-2"><a href="<?php echo admin_url() .'admin.php'. '?page=hub-order-custom-settings-page' ?>" class="btn btn-back">Back</a></div>
        <div class="col-md-10"><h2 class="text-center">Update Shop</h2></div>
        
        
        <form method="post" action="">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Shop Name</label>
            <input type="text" class="form-control" name="shop_name" value="<?php echo $shop_row->shop_name ?>" aria-describedby="emailHelp" required>
            
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Shop URL</label>
            <input type="text" class="form-control" name="shop_url" value="<?php echo $shop_row->shop_url ?>" id="exampleInputPassword1" required>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Shop IP</label>
            <input type="text" class="form-control"name="shop_ip" value="<?php echo $shop_row->shop_ip ?>" id="exampleInputPassword1" required>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Shop Secret</label>
            <input type="text" class="form-control" name="shop_secret" value="<?php echo $shop_row->shop_secret ?>" id="exampleInputPassword1" required>
        </div>
        <select class="form-select mb-2" aria-label="Default select example" name="is_active" required>
            <option value="1" <?php echo $shop_row->is_active == 1 ? "selected":" " ?>>Active</option>
            <option value="0" <?php echo $shop_row->is_active == 0 ? "selected":" " ?>>InActive</option>
        </select>
        <button type="submit" class="btn btn-primary" name="submit_btn">Submit</button>
        </form>
       
    </div>
</div>