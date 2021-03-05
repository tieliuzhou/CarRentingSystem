<?php require_once ('../../private/initialize.php');
require_login();

$errors = [];

$history_result = find_by_cust_id_type($_SESSION['user_id'],$_SESSION['cust_type']); // find in hist_cust table

?>


<?php $page_title = 'History Page'; ?>
<?php require (SHARED_PATH . '/proj_header.php');?>

<div id="content">
    <div class="History listing">
        <h1>History Page</h1>
        <a class="back-link" href="<?php echo url_for('/pages/index.php'); ?>">&laquo; Back to Query</a>
        <?php echo display_errors($errors); ?>
        <table class="list">
            <tr>
                <th>History ID</th>
                <th>Invoice ID</th>
                <th>Service ID</th>
                <th>Payment</th> <!--通过inv_id 来查看payment记录-->
                <th>Pay</th>
            </tr>

            <?php while($hist_result = $history_result->fetch_assoc()) { ?>
                <?php
                    $hist_id = $hist_result['hist_id'];
                    $result_set = find_hist_by_his_id($hist_id); // find in history table
                ?>
                <?php while($hist = $result_set->fetch_assoc()){?>
                <tr>
                    <td><?php echo h($hist['hist_id']); ?></td>
                    <td><a class="show invoice" href="<?php echo url_for('/pages/invoice.php?inv_id='.h(u($hist['inv_id']))); ?>"><?php echo h($hist['inv_id']); ?></a></td>
                    <td><a class="show service" href="<?php echo url_for('/pages/service.php?serv_id='.h(u($hist['serv_id']))); ?>"><?php echo h($hist['serv_id']); ?></a></td>
                    <td><a class="show payment" href="<?php echo url_for('/pages/payment.php?inv_id='.h(u($hist['inv_id']))); ?>">View</a></td>
                    <td>
                        <?php
                            $left_amount = cal_left_amount_by_inv_id($hist['inv_id']);
                            //echo $left_amonut;
                            if($left_amount>0){ // 没付清 就可跳到pay页面付款
                                echo '<a class="show payment" href="'.url_for('/pages/pay.php?inv_id='.h($hist['inv_id']).'&left_amount='.h($left_amount)).'">Pay</a>';
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
                    </td>
                </tr>
                <?php } ?>
                <?php $result_set->free_result();?>
            <?php } ?>
        </table>

        <?php $history_result->free_result(); ?>

    </div>

</div>



<?php require (SHARED_PATH . '/proj_footer.php');?>
