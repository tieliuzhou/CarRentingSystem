<?php
require_once('../private/initialize.php');

$errors = [];
$username = '';
$password = '';

if(is_post_request()) {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $cust_type = $_POST['cust_type'] ?? '';
    $cust_type = strtoupper($cust_type);

    // Validations
    if(is_blank($username)) {
        $errors[] = "Username cannot be blank.";
    }
    if(is_blank($password)) {
        $errors[] = "Password cannot be blank.";
    }
    if(is_blank($cust_type)) {
        $errors[] = "customer type cannot be blank.";
    }
    if($cust_type != 'C' && $cust_type != 'I'){
        $errors[] = "Customer type can either be 'I' or 'C'";
    }

    // if there were no errors, try to login
    if(empty($errors)) {
        // Using one variable ensures that msg is the same
        $login_failure_msg = "Log in was unsuccessful.";

        $admin = find_user_by_username($username,1);

        //test $errors[] = "1" . $admin['user_id'] . " ; " . $_SESSION['user_id'];

        if($admin) {

            if(password_verify($password, $admin['hashed_password'])) {
                // password matches
                log_in_user($admin,1);
                $_SESSION['cust_type'] = $cust_type;
                redirect_to(url_for('/index.php'));
            } else {
                // username found, but password does not match
                $errors[] = $login_failure_msg . " Password is not incorrect.";
            }

            //$errors[] = "1 " . $admin['user_id'] . " ; " . $_SESSION['user_id'];

        } else {
            // no username found
            $errors[] = $login_failure_msg;
        }

    }

}

?>

<?php $page_title = 'Log in'; ?>
<?php include(SHARED_PATH . '/proj_header.php'); ?>
    <?php echo "<br /><br /><br /><br /><br /><br /><br /><br /><br />"; ?>
    <div id="content">
        <div style="width: auto;text-align: center;vertical-align: center;">
            <h1>Log in</h1>
            <?php echo display_errors($errors); ?>
            <comment>
            <form action="login.php" method="post">
                Username:<br />
                <input type="text" name="username" value="<?php echo h($username); ?>" /><br />
                Password:<br />
                <input type="password" name="password" value="" /><br />
                Individual or Corporation?(I/C):<br />
                <input type="text" name="cust_type" value="" /><br />
                <input type="submit" name="login" value="Log in"  />
                <button type="submit" id="register_button"><a href="register.php">Register</a></button>
                <button type="submit" id="admins_button"><a href="admins_login.php">Admins</a></button>
            </form>
            </comment>
        </div>
    </div>

<?php include(SHARED_PATH . '/proj_footer.php'); ?>