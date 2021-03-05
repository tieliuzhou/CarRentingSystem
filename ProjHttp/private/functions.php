<?php

function url_for($script_path){
    // add the leading '/' if not present
    // return the absolute path
    if($script_path[0] != '/'){
        $script_path = "/" . $script_path;
    }
    return WWW_ROOT . $script_path;
}

/* For Security */
function u($string=""){ // urlencode function for query
    //?后 转换特殊字符、保留字符 空格 = +
    return urlencode($string);
}

function raw_u($string=""){ // rawurlencode function for path
    //"?"前部分语句 空格 = %20
    return rawurlencode($string);
}

function h($string=""){
    //避免URL注入攻击，夹杂html指令或者javascrip命令
    //将"<"，">"等转换成别的字符"&lt","&gt"
    return htmlspecialchars($string);
}

/* For http*/
function error_404(){
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    exit();
}

function error_500(){
    header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error");
    exit();
}

function redirect_to($location){
    header("Location: ". $location);
    exit;
}

function is_post_request(){
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}
function is_get_request(){
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function is_blank($value) {
    return !isset($value) || trim($value) === '';
}

function display_errors($errors=array()) {
    $output = '';
    if(!empty($errors)) {
        $output .= "<div class=\"errors\">";
        $output .= "Please fix the following errors:";
        $output .= "<ul>";
        foreach($errors as $error) {
            $output .= "<li>" . h($error) . "</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return $output;
}

/*For Auth*/
// Performs all actions necessary to log in an admin
function log_in_user($admin,$option) {
    // Renerating the ID protects the admin from session fixation.
    session_regenerate_id();
    if($option==0)
        $_SESSION['admin_id'] = $admin['admin_id'];
    else
        $_SESSION['user_id'] = $admin['user_id'];
    $_SESSION['last_login'] = time();
    $_SESSION['username'] = $admin['username'];
    return true;
}

// Performs all actions necessary to log out
function log_out_user() {
    if(isset($_SESSION['admin_id'])) {
        unset($_SESSION['admin_id']);
    }
    if(isset($_SESSION['user_id'])){
        unset($_SESSION['user_id']);
    }
    unset($_SESSION['last_login']);
    unset($_SESSION['username']);
    //session_destroy(); // optional: destroys the whole session
    return true;
}

function require_login($option=1) {
    if(!is_logged_in($option)) {
        //echo !is_logged_in($option);
        redirect_to(url_for('/login.php'));
    } else {
        // Do nothing, let the rest of the page proceed
    }
}

function is_logged_in($option) {
    // Having a admin_id in the session serves a dual-purpose:
    // - Its presence indicates the admin is logged in.
    // - Its value tells which admin for looking up their record.
    if($option==0)
        return isset($_SESSION['admin_id']);
    else
        return isset($_SESSION['admin_id']) or isset($_SESSION['user_id']);
}

function validate_user($admin, $options=[]) {

    $password_required = $options['password_required'] ?? true;
    $errors = array();

    if(is_blank($admin['username'])) {
        $errors[] = "Username cannot be blank.";
    } elseif (!has_length($admin['username'], array('min' => 8, 'max' => 255))) {
        $errors[] = "Username must be between 8 and 255 characters.";
    } elseif (!has_unique_username($admin['username']/*, $admin['id'] ?? 0*/)) {
        $errors[] = "Username not allowed. Try another.";
    }

    if($password_required) {
        if(is_blank($admin['password'])) {
            $errors[] = "Password cannot be blank.";
        } elseif (!has_length($admin['password'], array('min' => 12))) {
            $errors[] = "Password must contain 12 or more characters";
        } elseif (!preg_match('/[A-Z]/', $admin['password'])) {
            $errors[] = "Password must contain at least 1 uppercase letter";
        } elseif (!preg_match('/[a-z]/', $admin['password'])) {
            $errors[] = "Password must contain at least 1 lowercase letter";
        } elseif (!preg_match('/[0-9]/', $admin['password'])) {
            $errors[] = "Password must contain at least 1 number";
        } elseif (!preg_match('/[^A-Za-z0-9\s]/', $admin['password'])) {
            $errors[] = "Password must contain at least 1 symbol";
        }

        if(is_blank($admin['confirm_password'])) {
            $errors[] = "Confirm password cannot be blank.";
        } elseif ($admin['password'] !== $admin['confirm_password']) {
            $errors[] = "Password and confirm password must match.";
        }
    }

    return $errors;
}

// has_unique_page_menu_name('History')
// * Validates uniqueness of pages.menu_name
// * For new records, provide only the menu_name.
// * For existing records, provide current ID as second arugment
//   has_unique_page_menu_name('History', 4)
function has_length($value, $options) {
    if(isset($options['min']) && !has_length_greater_than($value, $options['min'] - 1)) {
        return false;
    } elseif(isset($options['max']) && !has_length_less_than($value, $options['max'] + 1)) {
        return false;
    } elseif(isset($options['exact']) && !has_length_exactly($value, $options['exact'])) {
        return false;
    } else {
        return true;
    }
}

// has_length_greater_than('abcd', 3)
// * validate string length
// * spaces count towards length
// * use trim() if spaces should not count
function has_length_greater_than($value, $min) {
    $length = strlen($value);
    return $length > $min;
}

// has_length_less_than('abcd', 5)
// * validate string length
// * spaces count towards length
// * use trim() if spaces should not count
function has_length_less_than($value, $max) {
    $length = strlen($value);
    return $length < $max;
}

// has_length_exactly('abcd', 4)
// * validate string length
// * spaces count towards length
// * use trim() if spaces should not count
function has_length_exactly($value, $exact) {
    $length = strlen($value);
    return $length == $exact;
}

// has_unique_username('johnqpublic')
// * Validates uniqueness of admins.username
// * For new records, provide only the username.
// * For existing records, provide current ID as second argument
//   has_unique_username('johnqpublic', 4)
function has_unique_username($username/*, $current_id="0"*/) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE username='" . $db->real_escape_string($username) . "';";
    //$sql .= "AND id != '" . $db->real_escape_string($current_id) . "'";

    $result = $db->query($sql);
    $user_count = $result->num_rows;
    $result->free_result();

    return $user_count === 0;
}

function validate_date($date){
    $errors = [];

}

/*for db*/
function find_user_by_username($username, $option) {
    global $db;

    if($option==0)
        $sql = "SELECT * FROM admins ";
    else
        $sql = "SELECT * FROM users ";
    $sql .= "WHERE username='" . $db->real_escape_string($username) . "' ";
    $sql .= "LIMIT 1";
    $result = $db->query($sql);
    confirm_result_set($result);
    $admin = $result->fetch_assoc();// find first
    $result->free_result();
    return $admin; // returns an assoc. array
}

function user_register($user,$option=1){
    global $db;

    $errors = validate_user($user);
    if(!empty($errors)){
        return $errors;
    }

    //Encrypted password
    $hashed_password = password_hash($user['password'],PASSWORD_BCRYPT);

    $db->begin_transaction();
    try{
        if($option==0){
            $sql = "INSERT INTO admins";
        }
        else{
            $sql = "INSERT INTO users";
        }

        $sql .= "(username, hashed_password, is_register) ";
        $sql .= "VALUES (";
        $sql .= "'" . $db->real_escape_string($user['username']) . "',";
        $sql .= "'" . $db->real_escape_string($hashed_password) . "',";
        $sql .= "'" . $db->real_escape_string(0) . "')";
        $result = $db->query($sql);
        $db->commit();

        // For INSERT statements, $result is true/false
        if($result) {
            return true;
        } else {
            // INSERT failed
            echo $db->error;
            db_disconnect($db);
            exit;
        }
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
        echo $db->error;
        db_disconnect($db);
        exit;
    }

}

function find_all_users($option=1){
    global $db;

    if($option==0){
        $sql = "SELECT * FROM admins ";
        $sql .= "ORDER BY admin_id ASC;";
    }
    else{
        $sql = "SELECT * FROM users ";
        $sql .= "ORDER BY user_id ASC;";
    }
    $result = $db->query($sql);
    confirm_result_set($result);
    return $result;
}

function find_user_by_id($id,$option=1){
    global $db;

    if($option==0){
        $sql = "SELECT * FROM admins ";
        $sql .= "WHERE admin_id='";
    }
    else{
        $sql = "SELECT * FROM users ";
        $sql .= "WHERE user_id='";
    }
    $sql .= $db->real_escape_string($id) . "' ";
    $sql .= "LIMIT 1;";
    $result = $db->query($sql);
    confirm_result_set($result);
    $user = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $user; // returns an assoc. array
}

function update_user($user,$option=1){ // 定义transaction?
    global $db;

    $password_sent = !is_blank($user['password']);

    $errors = validate_user($user, ['password_required' => $password_sent]);
    if (!empty($errors)) {
        return $errors;
    }

    $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);

    $db->begin_transaction();
    try{
        if($option==0){
            $sql = "UPDATE admins SET ";
        }
        else{
            $sql = "UPDATE users SET ";
        }

        if($password_sent) {
            $sql .= "hashed_password='" . $db->real_escape_string($hashed_password) . "', ";
        }
        $sql .= "username='" . $db->real_escape_string($user['username']) . "' ";
        if($option==0){
            $sql .= "WHERE admin_id='";
            $sql .= $db->real_escape_string($user['admin_id']) . "' ";
        }
        else{
            $sql .= "WHERE user_id='";
            $sql .= $db->real_escape_string($user['user_id']) . "' ";
        }

        $sql .= "LIMIT 1";
        $result = $db->query($sql);


        // For UPDATE statements, $result is true/false
        if($result) {
            $db->commit();
            return true;
        } else {
            // UPDATE failed
            echo $db->error;
            db_disconnect($db);
            exit;
        }
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
        echo "Udated Failed: " . $db->error;
        db_disconnect($db);
        exit;
    }

}

function delete_user($id,$option=1){
    global $db;

    $db->begin_transaction();
    try{
        if($option==0){
            $sql = "DELETE FROM admins ";
            $sql .= "WHERE admin_id='";
        }
        else{
            $sql = "DELETE FROM users ";
            $sql .= "WHERE user_id='";

        }
        $sql .= $db->real_escape_string($id) . "' ";
        $sql .= "LIMIT 1;";
        $result = $db->query($sql);

        // For DELETE statements, $result is true/false
        if($result) {
            if($db->affected_rows == 1){
                $db->commit();
                return true;
            }
            else{
                echo "Delete Failed: " . $db->error;
                db_disconnect($db);
                exit;
            }
        } else {
            // DELETE failed
            echo "Delete Failed: " . $db->error;
            db_disconnect($db);
            exit;
        }
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
        echo "Delete Failed: " . $db->error;
        db_disconnect($db);
        exit;
    }

}

function register_state_change($id,$state=1){
    global $db;

    $db->begin_transaction();
    try{
        $sql = $db->prepare("UPDATE users SET is_register = ? WHERE user_id = ?");
        $sql->bind_param("ii",$state,$id);
        $sql->execute();
        $db->commit();
        return $db->affected_rows;
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
    }
    return 0;


}

function find_all_location(){
    global $db;

    $sql = "SELECT * FROM office_location ORDER BY loc_id ASC;";
    $result = $db->query($sql);
    confirm_result_set($result);
    return $result;
}

function find_all_city(){
    global $db;

    $sql = "SELECT DISTINCT loc_city FROM office_location ORDER BY 1 ASC;";
    $result = $db->query($sql);
    confirm_result_set($result);
    return $result;
}

function find_all_state(){
    global $db;

    $sql = "SELECT DISTINCT loc_state FROM office_location ORDER BY 1 ASC;";
    $result = $db->query($sql);
    confirm_result_set($result);
    return $result;
}

function find_all_class(){
    global $db;

    $sql = "SELECT DISTINCT class_name FROM class ORDER BY 1 ASC;";
    $result = $db->query($sql);
    confirm_result_set($result);
    return $result;
}

function find_vehicle_by_id($id){
    global $db;
    $sql = "SELECT * FROM vehicle ";
    $sql .= "WHERE veh_id='" . $db->real_escape_string($id) . "' ";
    $sql .= "LIMIT 1";
    $result = $db->query($sql);
    confirm_result_set($result);
    $vehicle = $result->fetch_assoc();
    $result->free_result();
    return $vehicle;
}

function find_vehicle($req){//
    global $db;

    /*$stmt = "SELECT * FROM vehicle WHERE veh_id IN ";
    $stmt .= "(SELECT veh_id FROM service WHERE pu_date > ? OR do_date < ?) ";
    $stmt .= "AND loc_id IN (SELECT loc_id FROM office_location WHERE loc_city = ? AND loc_state = ?) ";
    $stmt .= "AND class_id IN (SELECT class_id FROM class WHERE class_name = ?) ORDER BY veh_id";
    $sql = $db->prepare($stmt);
    $sql->bind_param("sssss",$req['drop_off_date'],$req['pick_up_date'],$req['pick_up_city'],$req['pick_up_state'],$req['class_of_car']);
    $sql->execute();
    $sql->store_result();
    $sql->fetch();*/

    $sql = "SELECT * FROM vehicle WHERE veh_id NOT IN ";
    $sql .= "(SELECT veh_id FROM service WHERE pu_date <= ";
    $sql .= "'".$db->real_escape_string($req['drop_off_date'])."' AND do_date >= ";
    $sql .= "'".$db->real_escape_string($req['pick_up_date'])."') ";
    $sql .= "AND loc_id IN (SELECT loc_id FROM office_location WHERE loc_city = ";
    $sql .= "'".$db->real_escape_string($req['pick_up_city'])."' AND loc_state = ";
    $sql .= "'".$db->real_escape_string($req['pick_up_state'])."') ";
    $sql .="AND class_id IN (SELECT class_id FROM class WHERE class_name = ";
    $sql .= "'".$db->real_escape_string($req['class_of_car'])."') ORDER BY veh_id";
    $result_set = $db->query($sql);

    return $result_set;

    /*$veh_array = [];
    while($sql->fetch()){
        $sql->bind_result($veh_array[]);
    }
    $sql->free_result();


    //echo $stmt."\n";
    echo $req['drop_off_date']." ".$req['pick_up_date']." ".$req['pick_up_city']." ".$req['pick_up_state']." ".$req['class_of_car'];

    return $veh_array;*/
    //return $sql;
}

function find_loc_ID_by_city_state($city,$state){
    global $db;

    /*$sql = $db->prepare("SELECT loc_id FROM office_location WHERE loc_city = ? AND loc_state = ? ORDER BY 1");
    $sql->bind_param("ss",$city,$state);
    $sql->execute();
    $sql->store_result();
    $sql->fetch();
    return $sql;*/
    $sql = "SELECT loc_id, loc_street FROM office_location WHERE loc_city = ";
    $sql .= "'".$db->real_escape_string($city)."'"." AND loc_state = '" . $db->real_escape_string($state) . "' ORDER BY 1";
    $result_set = $db->query($sql);
    $result_array = [];
    while($result=$result_set->fetch_assoc()){
        $result_array[] = $result;
    }
    $result_set->free_result();
    return $result_array;
}

function find_location_by_veh_id($id){
    global $db;

    $sql = "SELECT o.loc_street, o.loc_city, o.loc_state, o.loc_zipcode ";
    $sql .= "FROM office_location o, vehicle v ";
    $sql .= "WHERE v.veh_id = '" . $db->real_escape_string($id) . "' AND v.loc_id = o.loc_id";

    $result_set = $db->query($sql);
    confirm_result_set($result_set);
    $location = $result_set->fetch_assoc();
    $result_set->free_result();
    $loc = $location['loc_street'].", ".$location['loc_city'].", ";
    $loc .= $location['loc_state'].", ".$location['loc_zipcode'];
    return $loc;
}

function find_addr_id_by_other($addr){
    global $db;

    $sql = "SELECT * FROM address WHERE street=";
    $sql .= "'". $db->real_escape_string($addr['street']) . "' AND city='";
    $sql .= $db->real_escape_string($addr['city'])."' AND state='";
    $sql .= $db->real_escape_string($addr['state'])."' AND zipcode='";
    $sql .= $db->real_escape_string($addr['zipcode'])."'";

    $result_set = $db->query($sql);
    if(!$result_set){
        /*$sql_max_id = $db->prepare("SELECT max(addr_id) FROM address");
        $sql_max_id->execute();
        $sql_max_id->bind_result($id);
        $sql_max_id->fetch();
        $id+=1;*/
        $sql1 = "INSERT INTO address(street,city,state,zipcode) VALUES(";
        $sql1 .= "'".$db->real_escape_string($addr['street'])."','".$db->real_escape_string($addr['city'])."','".$db->real_escape_string($addr['state'])."','";
        $sql1 .= $db->real_escape_string($addr['zipcode'])."')";
        $result = $db->query(sql1);
        if($result===true)
            return $db->insert_id;
        else return 1;
    }
    else{
        $result = $result_set->fetch_assoc();
        $result_set->free_result();
        return $result['addr_id'];
    }
}

function find_odo_start_by_veh_id($id,$pu_date){
    global $db;

    $sql = "SELECT max(odo_end) AS new_start FROM service s JOIN vehicle v ON s.veh_id = v.veh_id WHERE s.veh_id = ";
    $sql .= "'".$db->real_escape_string($id)."' AND s.do_date < ";
    $sql .= "'".$db->real_escape_string($pu_date)."'";

    /*$sql = $db->prepare("SELECT max(odo_end) FROM service s JOIN vehicle v ON s.veh_id = v.veh_id WHERE s.veh_id = ? AND s.do_date < ?");
    $sql->bind_param("ss",$id,$pu_date);
    $sql->execute();
    $sql->bind_result($result);
    $sql->fetch();*/
    $result = $db->query($sql);
    if(!$result)
        return 0;
    return $result;
}

function find_by_inv_id($id){
    global $db;

    $sql = "SELECT * FROM invoice WHERE inv_id=";
    $sql .= "'".$db->real_escape_string($id)."' LIMIT 1";
    $result_set = $db->query($sql);
    return $result_set;
}

function find_hist_by_his_id($id){
    global $db;
    $sql = "SELECT * FROM history WHERE hist_id=";
    $sql .= "'".$db->real_escape_string($id)."' ORDER BY hist_id ASC";
    $result_set = $db->query($sql);
    return $result_set;
}

function find_by_cust_id_type($cust_id,$cust_type){
    global $db;

    $sql = "SELECT * FROM hist_cust WHERE cust_id=";
    $sql .= "'".$db->real_escape_string($cust_id)."' AND cust_type=";
    $sql .= "'".$db->real_escape_string($cust_type)."'";
    $result_set = $db->query($sql);
    return $result_set;
}

function find_serv_by_serv_id($id){
    global $db;

    $sql = "SELECT * FROM service WHERE serv_id=";
    $sql .= "'".$db->real_escape_string($id)."'";
    $result_set = $db->query($sql);
    return $result_set;
}

function find_pmt_by_inv_id($id){
    global $db;

    $sql = "SELECT * FROM payment WHERE inv_id=";
    $sql .= "'".$db->real_escape_string($id)."'";
    $result_set = $db->query($sql);
    return $result_set;
}


function register_customer($customer){
    global $db;

    $db->begin_transaction();
    try{
        $sql = "INSERT INTO customer VALUES(";
        $sql .= "'".$db->real_escape_string($customer['cust_id'])."',";
        $sql .= "'".$db->real_escape_string($customer['cust_type'])."',";
        $sql .= "'".$db->real_escape_string($customer['first_name'])."',";
        $sql .= "'".$db->real_escape_string($customer['last_name'])."',";
        $sql .= "'".$db->real_escape_string($customer['cust_phone'])."',";
        $sql .= "'".$db->real_escape_string($customer['cust_email'])."',";
        $sql .= "'".$db->real_escape_string($customer['cust_zipcode'])."',";
        $sql .= "'".$db->real_escape_string($customer['addr_id'])."')";

        $result_set = $db->query($sql);
        $db->commit();
        if($result_set===true && $db->affected_rows == 1){
            return true;
        }
        else return false;
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
    }
    return false;
}

function register_corporate_customer($corp){
    global $db;

    $db->begin_transaction();
    try{
        $sql = "INSERT INTO corporate_customer VALUES(";
        $sql .= "'".$db->real_escape_string($corp['cust_id'])."',";
        $sql .= "'".$db->real_escape_string($corp['cust_type'])."',";
        $sql .= "'".$db->real_escape_string($corp['emp_id'])."',";
        $sql .= "'".$db->real_escape_string($corp['corp_id'])."')";

        $result_set = $db->query($sql);

        $db->commit();
        if($result_set===true && $db->affected_rows == 1){
            return true;
        }
        else return false;
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
    }
    return false;

}

function register_individual_customer($ind){
    global $db;

    $db->begin_transaction();
    try{
        $sql = "INSERT INTO individual_customer VALUES(";
        $sql .= "'".$db->real_escape_string($ind['cust_id'])."',";
        $sql .= "'".$db->real_escape_string($ind['cust_type'])."',";
        $sql .= "'".$db->real_escape_string($ind['dln'])."',";
        $sql .= "'".$db->real_escape_string($ind['icn'])."',";
        $sql .= "'".$db->real_escape_string($ind['ipn'])."',";
        $sql .= "'".$db->real_escape_string($ind['coupon_id'])."',";
        $result_set = $db->query($sql);
        $db->commit();
        if($result_set===true && $db->affected_rows == 1){
            return true;
        }
        else return false;
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
    }
    return false;


}

function create_invoice($date,$amount){
    global $db;

    $db->begin_transaction();
    try{
        $sql = "INSERT INTO invoice(inv_date,amount) VALUES(";
        $sql .= "'".$db->real_escape_string($date)."','".$db->real_escape_string($amount)."')";

        $result_set = $db->query($sql);
        $new_id = $db->insert_id;
        $db->commit();
        return $new_id;
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
    }
    return 0;

}

function create_service($serv){
    global $db;

    $db->begin_transaction();
    try{
        $str = "INSERT INTO service(pu_date,do_date,odo_start,odo_end,dly_lim,veh_id,cust_id,cust_type,inv_id,pu_loc_id,do_loc_id) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $str_para = $serv['pu_date'].",".$serv['do_date'].",".$serv['odo_start'].
            ",".$serv['odo_end'].",".$serv['dly_lim'].",".$serv['veh_id'].",".$serv['cust_id'].",".$serv['cust_type'].",".$serv['inv_id'].",".
            $serv['pu_loc_id'].",".$serv['do_loc_id'];
        $sql = $db->prepare($str);
        $sql->bind_param("sssssssssss",$serv['pu_date'],$serv['do_date'],$serv['odo_start'],
            $serv['odo_end'],$serv['dly_lim'],$serv['veh_id'],$serv['cust_id'],$serv['cust_type'],$serv['inv_id'],
            $serv['pu_loc_id'],$serv['do_loc_id']
        );
        /*$sql = "INSERT INTO service(pu_date,do_date,odo_start,odo_end,dly_lim,veh_id,cust_id,cust_type,inv_id,pu_loc_id,do_loc_id) ";
        $sql .= "VALUES('".$db->real_escape_string()."','";
        $sql .= $db->real_escape_string()."','";
        $sql .= $db->real_escape_string()."','";
        $sql .= $db->real_escape_string()."','";
        $sql .= $db->real_escape_string()."','";
        $sql .= $db->real_escape_string()."','";
        $sql .= $db->real_escape_string()."','";
        $sql .= $db->real_escape_string()."','";
        $sql .= $db->real_escape_string()."','";
        $sql .= $db->real_escape_string()."','";
        $sql .= $db->real_escape_string()."')";

        $result_set = $db->query($sql);*/
        $sql->execute();
        $new_id = $db->insert_id;
        $db->commit();
        //return $str_para;
        return $new_id;
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
    }
    return 0;

}

function create_history($inv_id, $serv_id){
    global $db;

   /* $sql = $db->prepare("INSERT INTO history(inv_id,serv_id) VALUES (?,?)");
    $sql->bind_param("ii",$inv_id,$serv_id);
    $sql->execute();
    $sql->bind_result($result);*/
    $sql = "INSERT INTO history(inv_id,serv_id) VALUES (";
    $sql .= "'".$db->real_escape_string($inv_id)."','".$db->real_escape_string($serv_id)."')";
    $result_set = $db->query($sql);
    $new_id = $db->insert_id;
    return $new_id;
}

function create_hist_cust($cust_id,$cust_type,$hist_id){
    global $db;

    $db->begin_transaction();
    try{
        $sql = "INSERT INTO hist_cust VALUES(";
        $sql .= "'".$db->real_escape_string($hist_id)."',";
        $sql .= "'".$db->real_escape_string($cust_id)."',";
        $sql .= "'".$db->real_escape_string($cust_type)."')";
        $result_set = $db->query($sql);
        $db->commit();
        return $db->affected_rows;
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
    }
    return 0;

}

function cal_left_amount_by_inv_id($id){
    global $db;

    $sql = $db->prepare("SELECT sum(p.pmt_amt) FROM payment p JOIN invoice i ON p.inv_id = i.inv_id WHERE i.inv_id = ?");
    $sql->bind_param("i",$id);
    $sql->execute();
    $sql->bind_result($payment_sum);
    $sql->fetch();
    if(!isset($payment_sum))
        $payment_sum = 0;
    $sql->free_result();

    $inv_result_set = find_by_inv_id($id);
    /*if(!$inv_result_set)
        $amount = -1;
    else{*/
    $result = $inv_result_set->fetch_assoc();
    $amount = $result['amount'];
    $inv_result_set->free_result();
    //}

    //return $amount;
    //return $payment_sum;
    return ($amount-$payment_sum);
}

function cal_amount($para){
    global $db;

    $sql = $db->prepare("SELECT daily_rate, over_limit_fee FROM class WHERE class_id = (SELECT class_id FROM vehicle WHERE veh_id = ?) LIMIT 1");
    $sql->bind_param("i",$para['veh_id']);
    $sql->bind_result($daily_rate,$over_limit_fee);
    $sql->execute();
    $sql->fetch();

    $amount = $para['days'] * $daily_rate + $para['over_limit_distance'] * $over_limit_fee;
    return $amount;

}

function create_payment($pay){
    global $db;

    //$db->autocommit(FALSE);
    $db->begin_transaction();
    try{
        $sql = "INSERT INTO payment(pmt_date,pmt_type,pmt_amt,card_num,inv_id) VALUES (";
        $sql .= "'".$db->real_escape_string($pay['pmt_date'])."',";
        $sql .= "'".$db->real_escape_string($pay['pmt_type'])."',";
        $sql .= "'".$db->real_escape_string($pay['pmt_amt'])."',";
        $sql .= "'".$db->real_escape_string($pay['card_num'])."',";
        $sql .= "'".$db->real_escape_string($pay['inv_id'])."')";
        $result_set = $db->query($sql);
        if(cal_left_amount_by_inv_id($pay['inv_id']) < 0){
            $db->rollback();
            return false;
        }

        else {
            $db->commit();
            return $result_set;
        }
    }
    catch (mysqli_sql_exception $exception){
        $db->rollback();
        throw $exception;
    }
    return 0;

}


function test(){
    global $db;

    $sql = "SELECT a.username, b.username FROM admins a, admins b";
    $result_set = $db->query($sql);
    return $result_set;
}
