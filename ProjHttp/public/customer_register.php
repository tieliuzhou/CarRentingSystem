<?php require_once('../private/initialize.php');
require_login();



$errors = [];
if(isset($_GET['errors'])){
    $errors[] = $_GET['errors'];
}

if(is_post_request()){
    $address['street'] = $_POST['street'];
    $address['city'] = $_POST['city'];
    $address['state'] = $_POST['state'];
    $address['zipcode'] = $_POST['zipcode'];
    $address['addr_id'] = find_addr_id_by_other($address);

    $customer['cust_id'] = $_SESSION['user_id'];
    $customer['cust_type'] = strtoupper($_POST['cust_type']);
    $customer['first_name'] = $_POST['first_name'];
    $customer['last_name'] = $_POST['last_name'];
    $customer['cust_phone'] = $_POST['cust_phone'];
    $customer['cust_email'] = $_POST['cust_email'];
    $customer['cust_zipcode'] = $_POST['cust_zipcode'];
    $customer['addr_id'] = $address['addr_id'];

    if('C'!=$customer['cust_type'] && 'I'!=$customer['cust_type']){
        redirect_to(url_for('/customer_register.php?errors=' . "Customer Type Wrong!"));
    }


    $result = register_customer($customer);
    if($customer['cust_type']=='I'){
        $_SESSION['cust_type'] = 'I';
        $individual['cust_id'] = $customer['cust_id'];
        $individual['cust_type'] = $customer['cust_type'];
        $individual['dln'] = $_POST['dln'];
        $individual['icn'] = $_POST['icn'];
        $individual['ipn'] = $_POST['ipn'];
        if(isset($_POST['coupon_id']))
            $individual['coupon_id'] = $_POST['coupon_id'];
        $result_ind = register_individual_customer($individual);
        if($result_ind===true){
            $_SESSION['message'] = 'Individual Customer created';
        }
        else{
            $errors[] = $db->error;
        }
    }
    else if($customer['cust_type']=='C'){
        $_SESSION['cust_type'] = 'C';
        $corporate['cust_id'] = $customer['cust_id'];
        $corporate['cust_type'] = $customer['cust_type'];
        $corporate['emp_id'] = $customer['emp_id'];
        $corporate['corp_id'] = $customer['corp_id'];
        $result_corp = register_corporate_customer($corporate);
        if($result_corp===true){
            $_SESSION['message'] = 'Corporate Customer created';
        }
        else{
            $errors[] = $db->error;
        }
    }

    if($result===true){
        $register_state_chane_result = register_state_change($_SESSION['user_id']);
        $new_id = $db->insert_id;
        $_SESSION['message'] = 'Customer created';
        redirect_to(url_for('/pages/index.php?id=' . $new_id));
    }
    else{
        $errors[] = $db->error;
    }
}
else{
    $customer = [];
    $customer['cust_id'] = $_SESSION['user_id'];
    $customer['cust_type'] = '';
    $customer['first_name'] = '';
    $customer['last_name'] = '';
    $customer['cust_phone'] = '';
    $customer['cust_email'] = '';
    $customer['cust_zipcode'] = '';
    $customer['addr_id'] = '';

    $address = [];
    $address['street'] = '';
    $address['city'] = '';
    $address['state'] = '';
    $address['zipcode'] = '';
    $address['addr_id'] = '';

    $individual = [];
    $individual['cust_id'] = '';
    $individual['cust_type'] = '';
    $individual['dln'] = '';
    $individual['icn'] = '';
    $individual['ipn'] = '';
    $individual['coupon_id'] = '';

    $corporate = [];
    $corporate['cust_id'] = '';
    $corporate['cust_type'] = '';
    $corporate['emp_id'] = '';
    $corporate['corp_id'] = '';

}


?>

<?php $page_title = 'Customer Register'; ?>
<?php require(SHARED_PATH . '/proj_header.php');?>
<div id="content">

    <a class="back-link" href="<?php echo url_for('/index.php'); ?>">&laquo; Back to List</a>

    <div class="customer register">
        <h1>Fill in your information</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/customer_register.php'); ?>" method="post">

            <dl>
                <dt>individual or corporate?(I/C)</dt>
                <dd><input type="text" name="cust_type" value="<?php echo h($customer['cust_type']); ?>" /></dd>
            </dl>
            <dl>
                <dt>First Name</dt>
                <dd>
                    <input type="text" name="first_name" value="<?php echo h($customer['first_name']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Last Name</dt>
                <dd>
                    <input type="text" name="last_name" value="<?php echo h($customer['last_name']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Phone Number</dt>
                <dd>
                    <input type="number" name="cust_phone" value="<?php echo h($customer['cust_phone']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Email</dt>
                <dd>
                    <input type="text" name="cust_email" value="<?php echo h($customer['cust_email']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Street</dt>
                <dd>
                    <input type="text" name="street" value="<?php echo h($address['street']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>City</dt>
                <dd>
                    <input type="text" name="city" value="<?php echo h($address['city']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>State</dt>
                <dd>
                    <input type="text" name="state" value="<?php echo h($address['state']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>zipcode</dt>
                <dd>
                    <input type="number" name="zipcode" value="<?php echo h($address['zipcode']); ?>" />
                </dd>
            </dl>
            <p>
                If you are individual, please fill in the following:
            </p>

            <dl>
                <dt>Driver License Number</dt>
                <dd>
                    <input type="number" name="dln" value="<?php echo h($individual['dln']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Insurance Company</dt>
                <dd>
                    <input type="text" name="icn" value="<?php echo h($individual['icn']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Insurance Policy Number</dt>
                <dd>
                    <input type="number" name="ipn" value="<?php echo h($individual['ipn']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Coupon ID(if you have)</dt>
                <dd>
                    <input type="number" name="coupon_id" value="<?php echo h($individual['coupon_id']); ?>" />
                </dd>
            </dl>

            <p>
                If you are corporate customer, please fill in the following:
            </p>
            <dl>
                <dt>Employ ID</dt>
                <dd>
                    <input type="number" name="emp_id" value="<?php echo h($individual['dln']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Corporate ID</dt>
                <dd>
                    <input type="number" name="corp_id" value="<?php echo h($individual['dln']); ?>" />
                </dd>
            </dl>


            <br />

            <div id="operations">
                <input type="submit" value="Register" />
            </div>
        </form>





    </div>

</div>
<?php require(SHARED_PATH . '/proj_footer.php');?>
