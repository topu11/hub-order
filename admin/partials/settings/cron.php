<?php
if(isset($_POST['submit_btn']))
{
    //var_dump($_POST);
    update_option('sync_order_cron_time',$_POST['cron_name']);
    wp_clear_scheduled_hook('hub_order_sync_cron_event');
    
    wp_schedule_event(time(), $_POST['cron_name'], 'hub_order_sync_cron_event');
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
        <div class="col-md-10"><h2 class="text-center">Set Up Cron Time</h2></div>
        
        
        <form method="post" action="">

        <label for="exampleInputEmail1" class="form-label">Cron Time (in minitue)</label>
        <select class="form-select mb-2" aria-label="Default select example" name="cron_name" required>
            <option value="every_one_minutes" <?php echo  get_option('sync_order_cron_time') == 'every_one_minutes' ? 'selected' :'' ?> >every 1 min</option>
            <option value="every_two_minutes" <?php echo  get_option('sync_order_cron_time') == 'every_two_minutes' ? 'selected' :'' ?> >every 2 min</option>
            <option value="every_three_minutes" <?php echo  get_option('sync_order_cron_time') == 'every_three_minutes' ? 'selected' :'' ?>>every 3 min</option>
            <option value="every_five_minutes" <?php echo  get_option('sync_order_cron_time') == 'every_five_minutes' ? 'selected' :'' ?>>every 5 min</option>
            <option value="every_ten_minutes" <?php echo  get_option('sync_order_cron_time') == 'every_ten_minutes' ? 'selected' :'' ?>>every 10 min</option>
            <option value="every_thirty_minutes" <?php echo  get_option('sync_order_cron_time') == 'every_thirty_minutes' ? 'selected' :'' ?>>every 30 min</option>
            <option value="every_sixty_minutes" <?php echo  get_option('sync_order_cron_time') == 'every_sixty_minutes' ? 'selected' :'' ?>>every 60 min</option>
        </select>
        <button type="submit" class="btn btn-primary" name="submit_btn">Submit</button>
        </form>
       
    </div>
</div>