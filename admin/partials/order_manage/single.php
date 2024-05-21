<?php
$billing_address=get_post_meta($post_order_id,'billing_address',true);
$shipping_address=get_post_meta($post_order_id,'shipping_address',true);
$billing_details=json_decode($billing_address);
$shipping_details=json_decode($shipping_address);

$order_note_content_admin=get_post_meta($post_order_id,'order_note_content_admin',true);
$note_by_customer=get_post_meta($post_order_id,'note_by_customer',true);
$products=get_post_meta($post_order_id,'products',true);
//var_dump($order_note_content_admin);
$json_decoded_products=json_decode($products);
$currency="USD ($)";

?>

 <!-- Order Details in General  -->

<div class="contaier">
    <h4 class="text-center">Edit Order for <?=_e($single_post->post_title)?></h4>
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4">
                        <h5 class="mb-2">General</h5>
                        <p class="hub-margin-bottom">Order date <?=get_post_meta($post_order_id,'order_date',true)?></p>
                        <p class="hub-margin-bottom">Shipping date <?=get_post_meta($post_order_id,'shipping_date',true)?></p>
                        <label for="" class="form-label">Order Status</label>
                        <select class="form-select" aria-label="Default select example" id="order_status">
                            <?php
                            foreach($cusstom_post_status as $key=>$value) 
                            {
                                ?>
                                <option value="<?=$key?>" <?php echo $key==$single_post->post_status ? "selected" :" " ?> ><?=$value?></option>
                                <?php
                            }
                            ?>
                            </select>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-2">Billing</h5>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_first_name;  ?></p>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_last_name; ?></p>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_email; ?></p>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_company; ?></p>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_address_1; ?></p>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_address_2; ?></p>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_city; ?></p>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_state; ?></p>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_postcode; ?></p>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_country; ?></p>
                    <p class="hub-margin-bottom"><?php echo $billing_details->billing_phone; ?></p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-2">Shipping</h5>
                    <?php
                    $shipping_attribute=[
                        'shipping_first_name','shipping_last_name','shipping_email','shipping_company','shipping_address_1','shipping_address_2',
                        'shipping_address_2','shipping_city','shipping_state','shipping_postcode','shipping_country','shipping_phone'
                    ]; 
                    foreach($shipping_attribute as $value)
                    {
                        $attribute=$value;
                        if(property_exists($shipping_details,$attribute))
                        {
                            ?>
                            <p class="hub-margin-bottom"><?php echo  $shipping_details->$attribute;  ?></p>
                            <?php
                        }
                    }
                    ?>
                
                
                </div>
            </div>
    </div>
        <div class="col-md-3" id="hub_note_section">
        <h5 class="mb-2" >Order Notes</h5>
             <p>Note by Custome : <?=$note_by_customer?></p>
             <p>Notes by Store </p>
             <?php 
             foreach(json_decode($order_note_content_admin) as $value)
             {
                ?>
                  <p><?php echo $value; ?></p>
                <?php
             }  
             ?>
        </div>
   </div>
</div>     


 <!-- Product details  -->
 <div class="container mt-5 bg-secondary">
    <div class="row">
        <div class="col-md-6">
            <h3 class="text-center">Product Details</h3>
            <table class="table" style="border: 1px solid var(--ast-border-color);">
                <tr>
                    <td>Item</td>
                    <td>Cost</td>
                    <td>Qty</td>
                    <td>Total</td>
                </tr>
             <?php
             $order_total=0;
             foreach($json_decoded_products as $key=>$value)
             {
                $order_total=$order_total+$value->total;
                ?>
                <tr>
                    <td><a class="text-decoration-none text-dark" href="<?=esc_url($value->permalink)?>"><img src="<?=$value->image_url?>" alt="" class="w-25"> <p><?=$value->product_name?> <br>SKU: <?=$value->sku?></p></a></td>
                    <td><?php echo $currency ?> <?php echo $value->price ?></td>
                    <td>X <?=$value->quantity?></td>
                    <td><?=$value->total?></td>
                </tr>
                <?php
             }
             ?>
             <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Order Total <?=$order_total?></td>
             </tr>
             </table>
        </div>
        <div class="col-md-6">
        <h3 class="text-center">Add Order Note Section </h3>
            <label for="" class="form-label">Add Note</label>
            <input type="text" class="form-control" id="hub_order_add_note_content">
            <button type="button" class="btn btn-primary mt-2" id="hub_order_add_note_button" post_order_id="<?=$post_order_id?>">Add Note</button>
        </div>
    </div>
 </div>