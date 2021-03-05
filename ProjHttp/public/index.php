<?php require_once ('../private/initialize.php');?>
<?php require_login();

/*$option = 1;
if(isset($_SESSION['admin_id'])){
    $id = $_SESSION['admin_id'];
    $option = 0;
}
else {
    $id = $_SESSION['user_id'];
}*/

?>
<?php $page_title = 'Public Page'; ?>
<?php require (SHARED_PATH . '/proj_header.php');?>
    <div id="content">
        <!--<?php echo $_SERVER['SCRIPT_NAME'];?>-->
        <!-- user login register logout admins-->
        <div style="text-align: center;">
            <h2>
                <a class="action" href="<?php echo url_for('/pages/index.php'); ?>">Order Now</a>
            </h2>
            <a href="<?php echo url_for('/index.php'); ?>">
                <img src="<?php echo url_for('/images/car2.jpeg'); ?>"/>
            </a>
        </div>
    </div>
<?php require (SHARED_PATH . '/proj_footer.php');?>