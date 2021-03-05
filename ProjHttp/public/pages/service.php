<?php require_once ('../../private/initialize.php');
require_login();


$errors = [];
if(isset($_GET['serv_id'])){
    $serv_id = $_GET['serv_id'];
    $service_set = find_serv_by_serv_id($serv_id);
    if(!$service_set){
        //redirect_to(url_for('/pages/history.php'));
    }
    $service = $service_set->fetch_assoc();
}
else{
    $errors[] = "No Service ID";
    redirect_to(url_for('/pages/history.php'));
}

?>


<?php $page_title = 'Show Service'; ?>
<?php include(SHARED_PATH . '/proj_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/pages/history.php'); ?>">&laquo; Back to History</a>

    <div class="service show">

        <h1>Service ID: <?php echo h($serv_id); ?></h1>

        <div class="attributes">
            <dl>
                <dt>Pick Up Date</dt>
                <dd><?php echo h($service['pu_date']); ?></dd>
            </dl>
            <dl>
                <dt>Drop Off Date</dt>
                <dd><?php echo h($service['do_date']); ?></dd>
            </dl>
            <dl>
                <dt>The Start Odometer</dt>
                <dd><?php echo h($service['odo_start']); ?></dd>
            </dl>
            <dl>
                <dt>The End Odometer</dt>
                <dd><?php echo h($service['odo_end']); ?></dd>
            </dl>
            <dl>
                <dt>Daily Limit</dt>
                <dd><?php echo h($service['dly_lim']); ?></dd>
            </dl>
            <dl>
                <dt>Vehicle ID</dt>
                <dd><?php echo h($service['veh_id']); ?></dd>
            </dl>
            <dl>
                <dt>Customer ID</dt>
                <dd><?php echo h($service['cust_id']); ?></dd>
            </dl>
            <dl>
                <dt>Customer Type</dt>
                <dd><?php echo h($service['cust_type']); ?></dd>
            </dl>
            <dl>
                <dt>Invoice ID</dt>
                <dd><?php echo h($service['inv_id']); ?></dd>
            </dl>
            <dl>
                <dt>Pick Up Location ID</dt>
                <dd><?php echo h($service['pu_loc_id']); ?></dd>
            </dl>
            <dl>
                <dt>Drop Off Location ID</dt>
                <dd><?php echo h($service['do_loc_id']); ?></dd>
            </dl>
        </div>

    </div>

</div>
<?php include(SHARED_PATH . '/proj_footer.php'); ?>
