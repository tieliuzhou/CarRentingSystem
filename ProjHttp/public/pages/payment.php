<?php require_once ('../../private/initialize.php');
require_login();


$errors = [];
if(isset($_GET['inv_id'])){
    $inv_id = $_GET['inv_id'];
    $left_amount = cal_left_amount_by_inv_id($inv_id);
    //echo $left_amonut;
    $payment_set = find_pmt_by_inv_id($inv_id);
    if(!$payment_set){
        //redirect_to(url_for('/pages/history.php'));
    }
}
else{
    $errors[] = "No invoice ID";
    redirect_to(url_for('/pages/history.php'));
}

?>

<?php $page_title = 'Payment History Page'; ?>
<?php require (SHARED_PATH . '/proj_header.php');?>

<div id="content">
    <div class="Payment listing">
        <h1>Payment History</h1>
        <a class="back-link" href="<?php echo url_for('/pages/history.php'); ?>">&laquo; Back to History</a>
        <?php echo display_errors($errors); ?>
        <table class="list">
            <tr>
                <th>Payment ID</th>
                <th>Payment Time</th>
                <th>Payment Type</th>
                <th>Payment Amount</th>
                <th>Card Number</th>
            </tr>

            <?php while($payment = $payment_set->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo h($payment['pmt_id']); ?></td>
                    <td><?php echo h($payment['pmt_date']); ?></td>
                    <td><?php echo h($payment['pmt_type']); ?></td>
                    <td><?php echo h($payment['pmt_amt']); ?></td>
                    <td><?php echo h($payment['card_num']); ?></td>
                </tr>
            <?php } ?>
        </table>

        <?php $payment_set->free_result(); ?>

    </div>

</div>
<div style="text-align: right;">
    <div class="pay">
        <dl>
            <dt>Left Amount:</dt>
            <dd>
                <?php
                    $left_amount = cal_left_amount_by_inv_id($inv_id);
                    echo $left_amount." $";
                ?>
            </dd>
            <dt>
                <!-- 用form -->
                <?php
                $left_amount = cal_left_amount_by_inv_id($inv_id);
                //echo $left_amonut;
                if($left_amount>0){ // 没付清 就可跳到pay页面付款
                    echo '<a class="show payment" href="'.url_for('/pages/pay.php?inv_id='.h($inv_id).'&left_amount='.h(u($left_amount))).'">Pay</a>';
                    /*echo '<form action="'.url_for('/pages/pay.php').'"'.' method="post">';
                    echo '<input type="submit" value="Pay" />';
                    echo '<input type="hidden" name="left_amount" value="'.h($left_amount).'"/>';
                    echo '<input type="hidden" name="inv_id" value="'.h($hist['inv_id']).'"/>';
                    echo '</from>';*/
                }
                else{
                    echo "Paid";
                }
                ?>
            </dt>
        </dl>
    </div>
</div>
<?php include(SHARED_PATH . '/proj_footer.php'); ?>