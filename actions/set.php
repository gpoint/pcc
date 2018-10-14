<?php

include_once 'Data.php';

//var_dump($session_id);
//die();

//echo Data::getAction($_POST)." ".Data::restData($login_parameters);

$contact_set_entry_parameters = array(
    //session id
    "session" => $session_id,
    //The name of the module
    "module_name" => Data::getModule($_POST),
    //Record attributes
    "name_value_list" => Data::restData($_POST)
);

$contact_set_entry_result = call("set_entry", $contact_set_entry_parameters, $url);
//print_r($contact_set_entry_result);
if(Data::getModule($_POST) == 'Cases'){
    $complaint_id = Data::getComplaintID($complaint_hash);
//    Data::getComplaintID($complaint_id, $contact_id)
    echo "Your Complaint ID is ".$complaint_id."\n\nPlease keep it well. You might need it to check for updates on your case.";
}else{
//    print_r($contact_set_entry_result);
}
//var_dump($contact_set_entry_result);
//die();

//header('Location: http://192.168.2.112/fbn/index.php?message=success');
// echo "<pre>";
// print_r($record_response);
// echo "</pre>";


