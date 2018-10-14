<?php

    $url = "http://localhost/PCC/service/v4_1/soap.php?wsdl";
    $username = "admin";
    $password = "admin";

    //require NuSOAP
    require_once("nusoap/lib/nusoap.php");

    //retrieve WSDL
    $client = new nusoap_client($url, 'wsdl');

//    print_r($client);
    
    //display errors
    $err = $client->getError();
    if ($err)
    {
        echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
        exit();
    }

    //login ------------------------------------------------ 

    $login_parameters = array(
         'user_auth' => array(
              'user_name' => $username,
              'password' => md5($password),
//              'version' => '1'
         ),
         'application_name' => 'iNCRM',
         'name_value_list' => array(
         ),
    );

    $login_result = $client->call('login', $login_parameters);

    /*
    echo '<pre>';
    print_r($login_result);
    echo '</pre>';
    */

    //get session id
    $session_id =  $login_result['id'];
    
    echo $session_id;

    //get list of records ---------------------------------- 

    $get_entry_list_parameters = array(

         //session id
         'session' => $session_id,

         //The name of the module from which to retrieve records
         'module_name' => 'Cases',

         //The SQL WHERE clause without the word "where".
         'query' => "",

         //The SQL ORDER BY clause without the phrase "order by".
         'order_by' => "",

         //The record offset from which to start.
         'offset' => '0',

         //Optional. A list of fields to include in the results.
         'select_fields' => array(
              'id',
              'name',
//              'title',
         ),

         /*
         A list of link names and the fields to be returned for each link name.
         Example: 'link_name_to_fields_array' => array(array('name' => 'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address')))
         */
         'link_name_to_fields_array' => array(
         ),

         //The maximum number of results to return.
         'max_results' => 2,

         //To exclude deleted records
         'deleted' => 0,

         //If only records marked as favorites should be returned.
         'Favorites' => false,
    );

    $get_entry_list_result = $client->call('get_entry_list', $get_entry_list_parameters);

    echo '<pre>';
    print_r($get_entry_list_result);
    echo '</pre>';

?>