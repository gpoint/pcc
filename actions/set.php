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
if (Data::getModule($_POST) == 'Cases') {
//    print_r($contact_set_entry_result);
    $complaint_id = Data::getComplaintID($contact_set_entry_result->id);
//    echo $complaint_id;
//    Data::getComplaintID($complaint_id, $contact_id)
    echo "Your Complaint ID is " . $complaint_id . "\n\nPlease keep it well. You might need it to check for updates on your case.";
    
} else if (Data::getModule($_POST) == 'Contacts') {
    
    header('Content-Type: text/json');
//    print_r($contact_set_entry_result);
    $id = $contact_set_entry_result->id;
    $name = $contact_set_entry_result->entry_list->title->value
            .' '.$contact_set_entry_result->entry_list->first_name->value
            .' '.$contact_set_entry_result->entry_list->last_name->value;
    die(json_encode(array(
        'id' => $id,
        'name' => $name
    )));
//    die(Data::complainantExists($contact_set_entry_result, 'email1', $_POST['email1']));
//    print_r($contact_set_entry_result);
}
//var_dump($contact_set_entry_result);
//die();

//header('Location: http://192.168.2.112/fbn/index.php?message=success');
// echo "<pre>";
// print_r($record_response);
// echo "</pre>";


