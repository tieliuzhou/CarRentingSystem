<?php
require_once ('../../private/initialize.php');
require_login(0);

$errors = [];

if(is_post_request()) {
    $user['username'] = $_POST['username'] ?? '';
    $user['password'] = $_POST['password'] ?? '';
    $user['confirm_password'] = $_POST['confirm_password'] ?? '';

    $result = user_register($user,0);
    if($result === true) {
        $new_id = $db->insert_id;
        $_SESSION['message'] = 'User created.';
        redirect_to(url_for('/admins/show_admin.php?id=' . $new_id));
    } else {
        $errors = $result;
    }

} else {
    // display the blank form
    $user = [];
    $user["username"] = '';
    $user['password'] = '';
    $user['confirm_password'] = '';
}





?>

<?php $page_title = 'Create Admin'; ?>
<?php include(SHARED_PATH . '/proj_header.php'); ?>

    <div id="content">

        <a class="back-link" href="<?php echo url_for('/admins/index.php'); ?>">&laquo; Back to List</a>

        <div class="admin new">
            <h1>Create Admin</h1>

            <?php echo display_errors($errors); ?>

            <form action="<?php echo url_for('/admins/new_admin.php'); ?>" method="post">
                <dl>
                    <dt>Username</dt>
                    <dd><input type="text" name="username" value="<?php echo h($user['username']); ?>" /></dd>
                </dl>
                <dl>
                    <dt>Password</dt>
                    <dd><input type="password" name="password" value="" /></dd>
                </dl>

                <dl>
                    <dt>Confirm Password</dt>
                    <dd><input type="password" name="confirm_password" value="" /></dd>
                </dl>
                <p>
                    Passwords should be at least 12 characters and include at least one uppercase letter, lowercase letter, number, and symbol.
                </p>
                <br />

                <div id="operations">
                    <input type="submit" value="Create Admin" />
                </div>
            </form>

        </div>

    </div>

<?php include(SHARED_PATH . '/proj_footer.php'); ?>