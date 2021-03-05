<?php

require_once('../../private/initialize.php');

require_login(0);

if(!isset($_GET['id'])) {
    redirect_to(url_for('/admins/index.php'));
}
$id = $_GET['id'];
$errors = [];
if(is_post_request()) {
    $result = delete_user($id);

    if($result === true) {
        $_SESSION['message'] = 'User deleted.';
        redirect_to(url_for('/admins/index.php'));
    } else {
        $errors = $result;
    }

} else {
    $user = find_user_by_id($id);
}

?>

<?php $page_title = 'Delete User'; ?>
<?php include(SHARED_PATH . '/proj_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/admins/index.php'); ?>">&laquo; Back to List</a>

    <div class="user delete">
        <h1>Delete User</h1>
        <?php echo display_errors($errors); ?>
        <p>Are you sure you want to delete this user?</p>
        <p class="item"><?php echo h($user['username']); ?></p>
        <form action="<?php echo url_for('/admins/delete.php?id=' . h(u($user['user_id']))); ?>" method="post">
            <div id="operations">
                <input type="submit" name="submit" value="Delete User" />
            </div>
        </form>
    </div>

</div>

<?php include(SHARED_PATH . '/proj_footer.php'); ?>
