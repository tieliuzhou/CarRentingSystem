<?php
    if(!isset($page_title)) {$page_title = 'WOW Car Rental System';}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WOW Car Rental System<?php echo $page_title; ?></title>
    <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/staff.css'); ?>" />
    <!-- <script src="../stylesheets/js/bootstrap.js"></script> -->
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <!-- <link href="https://cdn.staticfile.org/twitter-bootstrap/4.5.3/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <!-- <script src="https://cdn.staticfile.org/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <!-- <script src="https://cdn.staticfile.org/twitter-bootstrap/4.5.3/js/bootstrap.min.js"></script> -->
</head>
<body>
<header>
    <h1>WOW Car Rental System</h1>
</header>


<navigation>
    <ul>
        <li>
            <a href="<?php echo h(url_for('/index.php')); ?>">Home</a>
        </li>
        <li>
            <a href="<?php echo h(url_for('/logout.php')); ?>">Logout</a>
        </li>
        <?php if(isset($_SESSION['admin_id'])) {
            echo '<li><a href="' . h(url_for('/admins/index.php')) . '">Admins Page</a></li>';
        } ?>
        <?php if(isset($_SESSION['user_id'])) {
            echo '<li><a href="' . h(url_for('/customer_register.php')) . '">Customer Registration</a></li>';
            echo '<li><a href="' . h(url_for('/pages/history.php')) . '">History</a></li>';
        } ?>

    </ul>
</navigation>
