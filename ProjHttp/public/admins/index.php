<?php
require_once ('../../private/initialize.php');
require_login(0);
$user_set = find_all_users();
$admin_set = find_all_users(0);
?>
<?php $page_title = 'Admins Page'; ?>
<?php require (SHARED_PATH . '/proj_header.php');?>
    <div id="content">
        <!--<?php echo $_SERVER['SCRIPT_NAME'];?>-->
        <!-- manage admin accounts-->
        <div class="admins listing">
            <h1>Admins</h1>
            <div class="actions">
                <a class="action" href="<?php echo url_for('/admins/new_admin.php'); ?>">Create New Admin</a>
            </div>

            <table class="list">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>&nbsp;View</th>
                    <th>&nbsp;Edit</th>
                    <th>&nbsp;Delete</th>
                </tr>

                <?php while($admin = mysqli_fetch_assoc($admin_set)) { ?>
                    <tr>
                        <td><?php echo h($admin['admin_id']); ?></td>
                        <td><?php echo h($admin['username']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/admins/show_admin.php?id=' . h(u($admin['admin_id']))); ?>">View</a></td>
                        <td><a class="action" href="<?php echo url_for('/admins/edit_admin.php?id=' . h(u($admin['admin_id']))); ?>">Edit</a></td>
                        <td><a class="action" href="<?php echo url_for('/admins/delete_admin.php?id=' . h(u($admin['admin_id']))); ?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>
            <?php
            $admin_set->free_result();
            ?>
        </div>
    </div>

    <div id="content">
        <!-- manage user accounts-->
        <div class="users listing">
            <h1>Users</h1>

            <div class="actions">
                <a class="action" href="<?php echo url_for('/admins/new.php'); ?>">Create New User</a>
            </div>

            <table class="list">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>&nbsp;View</th>
                    <th>&nbsp;Edit</th>
                    <th>&nbsp;Delete</th>
                </tr>

                <?php while($user = mysqli_fetch_assoc($user_set)) { ?>
                    <tr>
                        <td><?php echo h($user['user_id']); ?></td>
                        <td><?php echo h($user['username']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/admins/show.php?id=' . h(u($user['user_id']))); ?>">View</a></td>
                        <td><a class="action" href="<?php echo url_for('/admins/edit.php?id=' . h(u($user['user_id']))); ?>">Edit</a></td>
                        <td><a class="action" href="<?php echo url_for('/admins/delete.php?id=' . h(u($user['user_id']))); ?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>

            <?php
            $user_set->free_result();
            ?>
        </div>
    </div>
<?php require (SHARED_PATH . '/proj_footer.php');?>