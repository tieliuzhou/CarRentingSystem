<?php
require_once ('../../private/initialize.php');
require_login(0);

$errors = [];

$id = $_GET['id'] ?? '1';
$user = find_user_by_id($id);


?>

<?php $page_title = 'Show User'; ?>
<?php include(SHARED_PATH . '/proj_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/admins/index.php'); ?>">&laquo; Back to List</a>

    <div class="user show">

        <h1>User: <?php echo h($user['username']); ?></h1>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/admins/edit.php?id=' . h(u($user['user_id']))); ?>">Edit</a>
            <a class="action" href="<?php echo url_for('/admins/delete.php?id=' . h(u($user['user_id']))); ?>">Delete</a>
        </div>

        <div class="attributes">
            <dl>
                <dt>Username</dt>
                <dd><?php echo h($user['username']); ?></dd>
            </dl>
        </div>

    </div>

</div>