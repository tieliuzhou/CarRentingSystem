<?php require_once('../../private/initialize.php'); ?>

<?php
require_login();
if(is_post_request()){
    $inv_date = date('Y-m-d');
    $inv_amount = $_POST['amount'];
    $inv_id = create_invoice($inv_date,$inv_amount);
    $Service = [];
    $Service['pu_date'] = $_POST['pu_date'] ;
    //$Service['pu_date'] = strtotime($Service['pu_date']);
    $Service['do_date'] = $_POST['do_date'] ;
    //$Service['do_date'] = strtotime($Service['do_date']);
    $Service['odo_start'] = $_POST['odo_start'] ;
    $Service['odo_end'] = $_POST['odo_end'] ;
    $Service['dly_lim'] = $_POST['dly_lim'] ;
    $Service['veh_id'] = $_POST['veh_id'] ;
    $Service['cust_id'] = $_POST['cust_id'] ;
    $Service['cust_type'] = $_POST['cust_type'] ;
    $Service['inv_id'] = $inv_id ;
    $Service['pu_loc_id'] = $_POST['pu_loc_id'] ;
    $Service['do_loc_id'] = $_POST['do_loc_id'] ;
    $Service['serv_id'] = create_service($Service);
    /*if(isset($Service['serv_id']))
        echo $Service['serv_id']."<br />";*/

    $history = create_history($inv_id,$Service['serv_id']);
    /*if(isset($history))
        echo $history;*/
    $hist_cust = create_hist_cust($_SESSION['user_id'],$_SESSION['cust_type'],$history);
}
else{
    $inv_date = date('Y-m-d');
    if(isset($_GET['inv_id'])){
        $inv_id = $_GET['inv_id'];
        $result = find_by_inv_id($inv_id);
        if(!$result)
            redirect_to(url_for('/pages/index.php'));
        else{
            $res = $result->fetch_assoc();
            $inv_date = $res['inv_date'];
            $inv_amount = $res['amount'];
        }
    }
    else
        redirect_to(url_for('/pages/index.php'));
}


?>

<?php $page_title = 'Show Invoice'; ?>
<?php include(SHARED_PATH . '/proj_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/pages/index.php'); ?>">&laquo; Back to List</a>

    <div class="invoice show">

        <h1>Invoice: </h1>

        <div class="attributes">
            <dl>
                <dt>Invoice ID</dt>
                <dd><?php echo h($inv_id); ?></dd>
            </dl>
            <dl>
                <dt>Invoice Created Date</dt>
                <dd><?php echo h($inv_date); ?></dd>
            </dl>
            <dl>
                <dt>Amount</dt>
                <dd><?php echo h($inv_amount); ?></dd>
            </dl>
            <dl>
                <dt>Left Amount</dt>
                <dd><?php echo h($inv_amount); ?></dd>
            </dl>
        </div>

    </div>

</div>
<?php require (SHARED_PATH . '/proj_footer.php');?>