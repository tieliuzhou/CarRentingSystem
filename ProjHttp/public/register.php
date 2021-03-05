<?php
require_once('../private/initialize.php');

$errors = [];
$username = '';
$password = '';

if(is_post_request()) {

    //$username = $_POST['username'] ?? '';
    //$password = $_POST['password'] ?? '';

    $user['username'] = $_POST['username'] ?? '';
    $user['password'] = $_POST['password'] ?? '';
    $user['confirm_password'] = $_POST['confirm_password'] ?? '';

    $result = user_register($user);
    if($result === true){
        $new_id = $db->insert_id;
        $_SESSION['message'] = 'User created';
        redirect_to(url_for('/login.php?id=' . $new_id));
    }
    else{
        $errors = $result;
    }
}
else{
    // display the blank form
    $user = [];
    $user["username"] = '';
    $user['password'] = '';
    $user['confirm_password'] = '';
}

?>

<?php $page_title = 'Register'; ?>
<?php include(SHARED_PATH . '/proj_header.php'); ?>
    <div id="content">
        <a class="back-link" href="<?php echo url_for('/login.php'); ?>">&laquo; Back to List</a>
        <div style="width: auto;text-align: center;vertical-align: center;">
            <h1>Register</h1>
            <?php echo display_errors($errors); ?>
            <comment>
                <form action="register.php" method="post">
                    <a1>Username:</a1><br />
                    <input type="text" name="username" value="<?php echo h($user['username']); ?>" /><br />
                    <a1>Password:</a1><br />
                    <input type="password" name="password" value="" /><br />
                    <a1>Confirm Password</a1>:<br />
                    <input type="password" name="confirm_password" value="" /><br />
                    <p>
                    Passwords should be at least 12 characters and include at least one uppercase letter, lowercase letter, number, and symbol.
                    </p>
                    <input type="submit" name="register" value="Register"  />
                </form>
            </comment>
        </div>
    </div>

<?php include(SHARED_PATH . '/proj_footer.php'); ?>