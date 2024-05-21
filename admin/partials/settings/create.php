<?php
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
    $wpdb->insert($wp_hub_order_shop_registration, $data);
    echo '<script>alert("New shop added successfully")</script>';
}
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
        <div class="col-md-10"><h2 class="text-center">Add New Shop</h2></div>
        
        
        <form method="post" action="">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Shop Name</label>
            <input type="text" class="form-control" name="shop_name"  aria-describedby="emailHelp" required>
            
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Shop URL</label>
            <input type="text" class="form-control" name="shop_url" id="exampleInputPassword1" required>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Shop IP</label>
            <input type="text" class="form-control"name="shop_ip"  id="exampleInputPassword1" required>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Shop Secret</label>
            <input type="text" class="form-control" name="shop_secret" id="exampleInputPassword1" required>
        </div>
        <select class="form-select mb-2" aria-label="Default select example" name="is_active" required>
            <option value="1">Active</option>
            <option value="0">InActive</option>
        </select>
        <button type="submit" class="btn btn-primary" name="submit_btn">Submit</button>
        </form>
       
    </div>
</div>