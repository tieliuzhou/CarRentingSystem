<?php require_once ('../../private/initialize.php');
require_login();


$errors = [];

if(is_post_request()) {
    $payment['pmt_date'] = $_POST['pmt_date'];
    //$payment['pmt_date'] = strtotime($payment['pmt_date']);
    $payment['pmt_type'] = $_POST['pmt_type'];
    $payment['pmt_amt'] = $_POST['pmt_amt'];
    $payment['card_num'] = $_POST['card_num'];
    $payment['inv_id'] = $_POST['inv_id'];
    $payment['left_amount'] = $_POST['left_amount'];

    if($payment['pmt_amt']>$payment['left_amount'] || $payment['pmt_amt']==0) {
        $errors[] = "Check Your Payment Amount. (Do not enter zero or it is too much)";
    }
    else{
        $result = create_payment($payment);

        if($result == 1) {
            $_SESSION['message'] = 'Payment Created.';
            redirect_to(url_for('/pages/payment.php?inv_id='.h(u($payment['inv_id']))));
        } else {
            $errors[] = "Payment Created Failed!";
        }
    }

} else {
    if(!isset($_GET['inv_id']))
        redirect_to(url_for('/pages/history.php'));
    else
        $inv_id = $_GET['inv_id'];

    if(!isset($_GET['left_amount']))
        redirect_to(url_for('/pages/payment.php?inv_id='.h(u($inv_id))));
    else
        $left_amount = $_GET['left_amount'];

    $payment = [];
    $payment['pmt_date'] = '';
    $payment['pmt_type'] = '';
    $payment['pmt_amt'] = '';
    $payment['card_num'] = '';
    $payment['inv_id'] = $inv_id;
    $payment['left_amount'] = $left_amount;
}

?>

<?php $page_title = 'Pay'; ?>
<?php include(SHARED_PATH . '/proj_header.php'); ?>

    <div id="content">

        <a class="back-link" href="<?php echo url_for('/pages/history.php'); ?>">&laquo; Back to History</a>

        <div class="pay">
            <h1>Payment</h1>

            <?php echo display_errors($errors); ?>

            <form action="<?php echo url_for('/pages/pay.php'); ?>" method="post">
                <dl>
                    <dt>Payment Date</dt>
                    <dd><?php echo h(date('Y-m-d')); ?></dd>
                    <dd><input type="hidden" name="pmt_date" value="<?php echo h(date('Y-m-d H:i:s')); ?>" /></dd>
                </dl>
                <dl>
                    <dt>Left To Pay</dt>
                    <dd><?php echo $payment['left_amount'].' $'; ?></dd>
                    <dd><input type="hidden" name="left_amount" value="<?php echo h($payment['left_amount']); ?>" /></dd>
                </dl>
                <dl>
                    <dt>Invoice ID</dt>
                    <dd><?php echo $payment['inv_id']; ?></dd>
                    <dd><input type="hidden" name="inv_id" value="<?php echo h($payment['inv_id']); ?>" /></dd>
                </dl>

                <dl>
                    <dt>Payment Type(Credit = 'C', Debit = 'D')</dt>
                    <dd><input type="text" name="pmt_type" value="<?php echo h($payment['pmt_type']); ?>" /></dd>
                </dl>

                <dl>
                    <dt>Card Number</dt>
                    <dd><input type="number" name="card_num" value="<?php echo h($payment['card_num']); ?>" /><br /></dd>
                </dl>

                <dl>
                    <dt>Payment Amount</dt>
                    <dd><input type="number" name="pmt_amt" value="<?php echo h($payment['pmt_amt']); ?>" /></dd>
                </dl>

                <br />

                <div id="operations">
                    <input type="submit" value="Pay" />
                </div>
            </form>

        </div>

    </div>
<?php include(SHARED_PATH . '/proj_footer.php'); ?>