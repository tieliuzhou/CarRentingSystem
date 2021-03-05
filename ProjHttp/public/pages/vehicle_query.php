<?php require_once ('../../private/initialize.php');
require_login();


$errors = [];
if(is_post_request()){
    $requirement = [];
    $requirement['pick_up_city'] = $_POST['pick_up_city'];
    $requirement['drop_off_city'] = $_POST['drop_off_city'];
    $requirement['pick_up_state'] = $_POST['pick_up_state'];
    $requirement['drop_off_state'] = $_POST['drop_off_state'];
    $requirement['pick_up_year'] = $_POST['pick_up_year'];
    $requirement['pick_up_month'] = $_POST['pick_up_month'];
    $requirement['pick_up_day'] = $_POST['pick_up_day'];
    $requirement['drop_off_year'] = $_POST['drop_off_year'];
    $requirement['drop_off_month'] = $_POST['drop_off_month'];
    $requirement['drop_off_day'] = $_POST['drop_off_day'];
    $requirement['class_of_car'] = $_POST['class_of_car'];
    $requirement['estimate_distance'] = $_POST['estimate_distance'];

    if(!checkdate($requirement['pick_up_month'],$requirement['pick_up_day'],$requirement['pick_up_year'])
    || !checkdate($requirement['drop_off_month'],$requirement['drop_off_day'],$requirement['drop_off_year'])){
        $errors = "Date is wrong";
        redirect_to(url_for('/pages/index.php?errors=' . h(u($errors))));
    }
    if($requirement['estimate_distance']<0){
        $errors = "Estimate distance cannot less than zero.";
        redirect_to(url_for('/pages/index.php?errors=' . h(u($errors))));
    }

    $PU_date = (string)$requirement['pick_up_year'] . "/";
    $PU_date .= (string)$requirement['pick_up_month'] . "/";
    $PU_date .= (string)$requirement['pick_up_day'];
    $DO_date = (string)$requirement['drop_off_year'] . "/";
    $DO_date .= (string)$requirement['drop_off_month'] . "/";
    $DO_date .= (string)$requirement['drop_off_day'];
    //echo $PU_date;
    $PU_date = date('Y-m-d', strtotime(str_replace('-','/',$PU_date)));
    //echo $PU_date;
    $DO_date = date('Y-m-d', strtotime(str_replace('-','/',$DO_date)));
    //echo $DO_date;

    if($PU_date>$DO_date){
        $errors = "Drop off date must equal or greater than pick up date";
        redirect_to(url_for('/pages/index.php?errors=' . h(u($errors))));
    }

    $requirement['pick_up_date'] = $PU_date;
    $requirement['drop_off_date'] = $DO_date;

    //
    $do_loc = find_loc_ID_by_city_state($requirement['drop_off_city'],$requirement['drop_off_state']);
    /*while($do_loc->fetch()) {
        $do_loc->bind_result($result);
        echo "<option value=\"{$result}\"";
        echo ">{$result}</option>";
    }*/
    /*foreach ($do_loc as $loc){
        echo $loc['loc_id'];
    }*/


    $result_set = find_vehicle($requirement);

    //$result_set->bind_result($veh_id,$make,$model,$year,$vin,$lpn,$loc_id,$class_id);
    //echo $veh_id;


}
else{
    redirect_to(url_for('/pages/index.php'));
}


?>

<?php $page_title = 'Avaliable Vehicle'; ?>
<?php require (SHARED_PATH . '/proj_header.php');?>

<div id="content">
    <div class="avaliable vehicle listing">
        <h1>Avaliable Vehicle</h1>
        <a class="back-link" href="<?php echo url_for('/pages/index.php'); ?>">&laquo; Back to Requirement</a>
        <?php echo display_errors($errors); ?>
        <table class="list">
            <tr>
                <th>Vehicle ID</th>
                <th>Made</th>
                <th>Model</th>
                <th>Year</th>
                <th>Vehicle Identification Number</th>
                <th>License Plate Number</th>
                <th>Location</th>
                <th>Total Price</th>
                <th>Order</th>
            </tr>

            <?php while($vehicle = $result_set->fetch_assoc()) { ?>
                <?php
                    $dly_lim = 50; // 规定每日限行公里数
                    $estimate_distance = $requirement['estimate_distance']??0;
                    $result_odo = find_odo_start_by_veh_id($vehicle['veh_id'],$PU_date);
                    $odo_start = $result_odo->fetch_assoc();
                    $result_odo->free_result();

                    $cal_para = [];
                    $pu_date = new DateTime($PU_date);
                    $do_date = new DateTime($DO_date);
                    $cal_para['days'] = $do_date->diff($pu_date)->format("%a"); // renting days
                    $cal_para['veh_id'] = $vehicle['veh_id'];
                    $over_distance = $estimate_distance - $dly_lim*$cal_para['days'];
                    $cal_para['over_limit_distance'] = ($over_distance > 0) ? $over_distance : 0 ;
                    $amount = cal_amount($cal_para);
                ?>
                <tr>
                    <td><?php echo h($vehicle['veh_id']); ?></td>
                    <td><?php echo h($vehicle['make']); ?></td>
                    <td><?php echo h($vehicle['model']); ?></td>
                    <td><?php echo h($vehicle['year']); ?></td>
                    <td><?php echo h($vehicle['vin']); ?></td>
                    <td><?php echo h($vehicle['lpn']); ?></td>
                    <td><?php echo h(find_location_by_veh_id($vehicle['veh_id'])); ?></td>
                    <td>
                        <?php
                            echo h($amount);
                        ?>
                    </td>
                    <td>
                        <form action="<?php echo url_for('/pages/invoice.php'); ?>" method="post">
                            <input type="hidden" name="pu_date" value="<?php echo h($PU_date); ?>" />
                            <input type="hidden" name="pu_date" value="<?php echo h($PU_date); ?>" />
                            <input type="hidden" name="do_date" value="<?php echo h($DO_date); ?>" />
                            <input type="hidden" name="odo_start" value="<?php echo  h($odo_start['new_start']); ?>"/>
                            <input type="hidden" name="odo_end" value="
                            <?php $odo_end = $odo_start['new_start'] + $estimate_distance; echo  h($odo_end); ?>"/>
                            <input type="hidden" name="dly_lim" value="<?php echo $dly_lim;?>" />
                            <input type="hidden" name="veh_id" value="<?php echo h($vehicle['veh_id']); ?>" />
                            <input type="hidden" name="cust_id" value="<?php echo h($_SESSION['user_id']); ?>" />
                            <input type="hidden" name="cust_type" value="<?php echo h($_SESSION['cust_type'] ?? 'I'); ?>" />
                            <input type="hidden" name="pu_loc_id" value="<?php echo h($vehicle['loc_id']); ?>" />
                            <dl>
                                <dt>Drop off location:</dt>
                                <dd>
                                    <select name="do_loc_id">
                                        <?php
                                            foreach ($do_loc as $loc){
                                                echo "<option value=\"{$loc['loc_id']}\"";
                                                echo ">"."id:".$loc['loc_id']." street:".$loc['loc_street']."</option>";
                                            }
                                        ?>
                                    </select>
                                </dd>
                            </dl>
                            <input type="hidden" name="amount" value="<?php echo h($amount); ?>" />
                            <input type="submit" id="submit" name="order" value="Order">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <?php $result_set->free_result(); ?>

    </div>

</div>



<?php require (SHARED_PATH . '/proj_footer.php');?>
