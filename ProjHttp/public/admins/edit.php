<?php

require_once('../../private/initialize.php');

require_login(0);

if(!isset($_GET['id'])) {
    redirect_to(url_for('/admins/index.php'));
}
$id = $_GET['id'];
$errors = [];
if(is_post_request()) {
    $user = [];
    $user['user_id'] = $id;
    $user['username'] = $_POST['username'] ?? '';
    $user['password'] = $_POST['password'] ?? '';
    $user['confirm_password'] = $_POST['confirm_password'] ?? '';

    $result = update_user($user);
    if($result === true) {
        $_SESSION['message'] = 'User updated.';
        redirect_to(url_for('/admins/show.php?id=' . $id));
    } else {
        $errors = $result;
    }
} else {
    $user = find_user_by_id($id);
}

?>

<?php $page_title = 'Edit User'; ?>
<?php include(SHARED_PATH . '/proj_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/admins/index.php'); ?>">&laquo; Back to List</a>

    <div class="user edit">
        <h1>Edit User</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/admins/edit.php?id=' . h(u($id))); ?>" method="post">

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
                <input type="submit" value="Edit User" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/proj_footer.php'); ?>
