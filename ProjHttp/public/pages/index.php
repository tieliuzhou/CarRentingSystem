<?php require_once ('../../private/initialize.php');
require_login();

$city_set = find_all_city();
$do_city_set = find_all_city();
$state_set = find_all_state();
$do_state_set = find_all_state();
$class_set = find_all_class();

$errors = [];
if(isset($_GET['errors']))
    $errors[] = $_GET['errors'];
?>

<?php $page_title = 'Requirement'; ?>
<?php require (SHARED_PATH . '/proj_header.php');?>
<div id="content">

    <a class="back-link" href="<?php echo url_for('/pages/index.php'); ?>">&laquo; Back to List</a>

    <div class="requirement">
        <h1>Enter your requirement</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/pages/vehicle_query.php'); ?>" method="post">
            <dl>
                <dt>Pick Up City</dt>
                <dd>
                    <select name="pick_up_city">
                        <?php
                            while($result = $city_set->fetch_assoc()) {
                                echo "<option value=\"{$result['loc_city']}\"";
                                /*if($subject["position"] == $i) {
                                    echo " selected";
                                }*/
                                echo ">{$result['loc_city']}</option>";
                            }
                            $city_set->free_result();
                        ?>
                    </select>
                </dd>
            </dl>

            <dl>
                <dt>Drop Off City</dt>
                <dd>
                    <select name="drop_off_city">
                        <?php
                        while($result = $do_city_set->fetch_assoc()) {
                            echo "<option value=\"{$result['loc_city']}\"";
                            echo ">{$result['loc_city']}</option>";
                        }
                        $do_city_set->free_result();
                        ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Pick Up State</dt>
                <dd>
                    <select name="pick_up_state">
                        <?php
                        while($result = $state_set->fetch_assoc()) {
                            echo "<option value=\"{$result['loc_state']}\"";
                            /*if($subject["position"] == $i) {
                                echo " selected";
                            }*/
                            echo ">{$result['loc_state']}</option>";
                        }
                        $state_set->free_result();
                        ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Drop Off State</dt>
                <dd>
                    <select name="drop_off_state">
                        <?php
                        while($result = $do_state_set->fetch_assoc()) {
                            echo "<option value=\"{$result['loc_state']}\"";
                            echo ">{$result['loc_state']}</option>";
                        }
                        $do_state_set->free_result();
                        ?>
                    </select>
                </dd>
            </dl>

            <dl>
                <dt>Pick Up Date(Year/Month/Day)</dt>
                <dd>
                    <input type="number" name="pick_up_year" value="" />
                </dd>
                <dd>
                    <input type="number" name="pick_up_month" value=""/>
                </dd>
                <dd>
                    <input type="number" name="pick_up_day" value=""/>
                </dd>
            </dl>
            <dl>
                <dt>Drop Off (Year/Month/Day)</dt>
                <dd>
                    <input type="number" name="drop_off_year" value="" />
                </dd>
                <dd>
                    <input type="number" name="drop_off_month" value=""/>
                </dd>
                <dd>
                    <input type="number" name="drop_off_day" value=""/>
                </dd>
            </dl>

            <dl>
                <dt>Class of Car</dt>
                <dd>
                    <select name="class_of_car">
                        <?php
                        while($result = $class_set->fetch_assoc()) {
                            echo "<option value=\"{$result['class_name']}\"";
                            echo ">{$result['class_name']}</option>";
                        }
                        $class_set->free_result();
                        ?>
                    </select>
                </dd>
            </dl>

            <dl>
                <dt>Estimate Distance /miles</dt>
                <dd><input type="number" name="estimate_distance" value="" /></dd>
            </dl>


            <br />


            <div id="operations">
                <input type="submit" value="Query" />
            </div>
        </form>


    </div>

</div>
<?php require (SHARED_PATH . '/proj_footer.php');?>
