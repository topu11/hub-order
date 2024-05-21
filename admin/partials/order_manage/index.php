<?php

?>
<button class="btn btn-primary" id="hub_order_refresh_data">Refresh Data</button>

<?php


$table ='<table  id="myTable">';

$table .='<thead>';
$table .='<tr>';

foreach($meta_keys as $value)
{
    $table .='<td>'.ucfirst(str_replace('_',' ',$value)).'</td>';
}
$table .='</tr>';
$table .='</thead>';
$table .='<tbody>';
foreach($order_data as $oder_single)
{
  $table .='<tr>';
  foreach($meta_keys as $value)
 {
    if($value == "order_name")
    { 
        
        $single_oder_link=add_query_arg( [
            'post_order_id'=>$oder_single->post_order_id
        ],site_url('/manage-single-order') );

        $table .='<td> <a href="'.esc_url($single_oder_link).'" class="text-black">'.$oder_single->$value.'</a> </td>';
    }
    elseif($value == "order_status")
    {
        $table .='<td>'.$post_status_name_mapping[$oder_single->$value].'</td>'; 
    }
    else
    {
        $table .='<td>'.$oder_single->$value.'</td>'; 
    }
 }
 $table .='</tr>';
}
$table .='</tbody>';
$table .='</table>';

echo  $table;
