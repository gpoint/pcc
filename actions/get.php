<?php

include_once './Data.php';

//----------------------------------------------------------------------------------------------------//
// Get Array
//----------------------------------------------------------------------------------------------------//

if(Data::getModule($_POST) == 'Cases' && $_POST['action'] == 'status'){

    $complaint_id = $_POST['complaint_id'];
    $complainant_id = $_POST['complainant_id'];

    Data::getComplaintStatus($complaint_id, $complainant_id);
}
if(Data::getModule($_POST) == 'Contacts'){
    $array =  array('id', 'phone_mobile', 'email1', 'title', 'first_name', 'last_name');
}

$get_entry_parameters = array(
    //session id
    'session' => $session_id,
    //The name of the module from which to retrieve records
    'module_name' => Data::getModule($_POST),
    //The SQL WHERE clause without the word "where".
    'query' => '',
    //The SQL ORDER BY clause without the phrase "order by".
    'order_by' => "",
    //The record offset from which to start.
    'offset' => '0',
    //Optional. A list of fields to include in the results.
    'select_fields' => $array,
    /*
      A list of link names and the fields to be returned for each link name.
      Example: 'link_name_to_fields_array' => array(array('name' => 'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address')))
     */
    'link_name_to_fields_array' => array(),
    //The maximum number of results to return.
    'max_results' => '',
    //To exclude deleted records
    'deleted' => '0',
    //If only records marked as favorites should be returned.
    'Favorites' => false,
);

$contact_get_entry_result = call("get_entry_list", $get_entry_parameters, $url);
//print_r($contact_get_entry_result['name_value_list']);
//die();
//header('Location: http://192.168.2.112/fbn/index.php?message=success');
//echo "<pre>";
//print_r($contact_get_entry_result);
//echo "</pre>";


if ($_POST['module'] == 'Cases') {
}
if ($_POST['module'] == 'Contacts') {
//    print_r(Data::restData($_POST));
//    print_r($contact_get_entry_result);
    $email_err_msg = '2';
    $phone_err_msg = '3';

    $email = Data::complainantExists($contact_get_entry_result, "email1", $_POST['email1']);
    $email = $email == false ? $email_err_msg : $email;

    $phone = Data::complainantExists($contact_get_entry_result, "phone_mobile", $_POST['phone_mobile']);
    $phone = $phone == false ? $phone_err_msg : $phone;

    
    if ($phone == $email) {
        header('Content-Type: text/json');
        die($phone);
    } else if ($phone == $phone_err_msg && $email == $email_err_msg) {
        die("1");
    } else {
        die($email == $email_err_msg ? $email : '3');
    }
}
 











