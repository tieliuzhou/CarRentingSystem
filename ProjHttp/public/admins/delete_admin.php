<?php

require_once('../../private/initialize.php');

require_login(0);

if(!isset($_GET['id'])) {
    redirect_to(url_for('/admins/index.php'));
}
$id = $_GET['id'];
$errors = [];
if(is_post_request()) {
    $result = delete_user($id,0);

    if($result === true) {
        $_SESSION['message'] = 'User deleted.';
        redirect_to(url_for('/admins/index.php'));
    } else {
        $errors = $result;
    }

} else {
    $user = find_user_by_id($id,0);
}

?>

<?php $page_title = 'Delete Admin'; ?>
<?php include(SHARED_PATH . '/proj_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/admins/index.php'); ?>">&laquo; Back to List</a>

    <div class="admin delete">
        <h1>Delete Admin</h1>
        <?php echo display_errors($errors); ?>
        <p>Are you sure you want to delete this user?</p>
        <p class="item"><?php echo h($user['username']); ?></p>
        <form action="<?php echo url_for('/admins/delete_admin.php?id=' . h(u($user['admin_id']))); ?>" method="post">
            <div id="operations">
                <input type="submit" name="submit" value="Delete User" />
            </div>
        </form>
    </div>

</div>

<?php include(SHARED_PATH . '/proj_footer.php'); ?>
