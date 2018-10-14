<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//----------------------------------------------------------------------------------------------------//

$url = "http://localhost/PCC/service/v4_1/rest.php";
$username = "admin";
$password = "admin";

//----------------------------------------------------------------------------------------------------//

function call($method, $parameters, $url) {

//----------------------------------------------------------------------------------------------------//

    ob_start();
    $curl_request = curl_init();

//----------------------------------------------------------------------------------------------------//

    curl_setopt($curl_request, CURLOPT_URL, $url);
    curl_setopt($curl_request, CURLOPT_POST, 1);
    curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($curl_request, CURLOPT_HEADER, 1);
    curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);

//----------------------------------------------------------------------------------------------------//

    $jsonEncodedData = json_encode($parameters);

//----------------------------------------------------------------------------------------------------//

    $post = array(
        "method" => $method,
        "input_type" => "JSON",
        "response_type" => "JSON",
        "rest_data" => $jsonEncodedData
    );

//----------------------------------------------------------------------------------------------------//

    curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($curl_request);
    curl_close($curl_request);

//----------------------------------------------------------------------------------------------------//

    $result = explode("\r\n\r\n", $result, 2);
    $response = json_decode($result[1]);
    ob_end_flush();

//----------------------------------------------------------------------------------------------------//

    return $response;
}

//----------------------------------------------------------------------------------------------------//
// Login Array
//----------------------------------------------------------------------------------------------------//
$login_parameters = array(
    "user_auth" => array(
        "user_name" => $username,
        "password" => md5($password),
        "version" => "1"
    ),
    "application_name" => "iNCRM",
    "name_value_list" => array(),
);

$login_result = call("login", $login_parameters, $url);

//get session id
$session_id = $login_result->id;

/**
 * Description of update
 *
 * @author Harry
 */
class Data {

    private static $host = "localhost";
    private static $user = "root";
    private static $password = "P@yr00ll";
    private static $database = "incrm";
    private static $port = 3306;
    private static $m;

    public static function getModule(array $param) {
        return $param['module'];
    }

    public static function restData(array $param) {
//----------------------------------------------------------------------------------------------------//
// Creating And Returning An Array Of Values Gotten From A Global Data Acception Array Variable       //
// e.g: _GET, _POST, _REQUEST.
//----------------------------------------------------------------------------------------------------//

        $array = array();
        if (1 > count($param)) {
            return $array;
        }

//----------------------------------------------------------------------------------------------------//

        foreach ($param as $key => $value) {
            $value = array("name" => $key, "value" => $value);
            array_push($array, $value);
        }

        return $array;
    }

    public static function getComplaintID($complaint_hash) {
        $query = "SELECT `case_number`  FROM `cases` WHERE id='$complaint_id'";
        $m = new mysqli(Data::host, Data::user, Data::password, Data::database);
//        mysqli_real_connect($link, $host, $username, $passwd, $dbname, $port)
        $result = mysqli_query($m, $query);
        $result_array = mysqli_fetch_assoc($result);
        return ($result_array['case_number']);
    }

    public static function getComplaintStatus($case_number, $contact_id) {
//        $array = array();
//----------------------------------------------------------------------------------------------------//
        $id_query = "SELECT `id`,`name`,`date_entered`,`state` FROM `cases` WHERE `case_number`='$case_number' LIMIT 1";
        $m = new mysqli(Data::$host, Data::$user, Data::$password, Data::$database);
//        mysqli_real_connect($link, $host, $username, $passwd, $dbname, $port)
        $id_result = mysqli_query($m, $id_query);

        if (!$id_result) {
            die(0);
        }

        $id_result_array = mysqli_fetch_assoc($id_result);

        $id = ($id_result_array['id']);

//        print_r($id_result_array);

        $status_query = "SELECT `region_c`,`incident_state_c`,`new_status_c` FROM `cases_cstm` WHERE `id_c`='$id' AND `contact_id_c`='$contact_id' LIMIT 1";

        $status_result = mysqli_query($m, $status_query);

        if (!$status_result) {
            die(0);
        }

        $status_result_array = mysqli_fetch_assoc($status_result);
        
        $array = array_merge($id_result_array, $status_result_array);

        header('Content-Type: text/json');
        die(json_encode($array));

        if (count($status_result_array) == 0) {
            die(0);
        }

//----------------------------------------------------------------------------------------------------//
    }

    function complainantExists($list, $param, $param_value) {

//        echo "$param   $param_value";
//        print_r(json_encode($list));
        foreach ($list->entry_list as $entry_item) {

//            print_r(json_encode($list->entry_list[1]));


            for ($index = 0; $index < count($list->entry_list); $index++) {
//                print_r(json_encode($list->entry_list[$index]->name_value_list));
//                echo "<br> <br> <br> <br> <br> <br> <br> ";
                $entry_detail = $list->entry_list[$index]->name_value_list;

                foreach ($entry_detail as $key => $value) {
//                    echo "<br> <br> <br> <br> <br> <br> <br> ";
//                    print_r($value);
                    if ($value->name == $param && $value->value == $param_value) {
//                        return (json_encode($entry_detail));
//                        print_r($entry_detail->id->value);
                        return json_encode(
                                array(
                                    'id' => $entry_detail->id->value,
                                    'name' => $entry_detail->title->value . ' ' . $entry_detail->first_name->value . ' ' . $entry_detail->last_name->value
                        ));
                    }
                }
            }
            return false;
        }
    }

//
}
