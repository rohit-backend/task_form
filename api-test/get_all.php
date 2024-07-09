
<?php
//REST API to fetch all the data in the db
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
header("Aceess-Control-Allow-Method: GET");
header("Aceess-Control-Allow-Headers: Content-Type, Aceess-Control-Allow-Headers, Authorisation, X-Request-with");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once("function_api.php");
    $data = getDataAll();
    echo $data;
} else {
    $data = [
        'status' => 405,
        'message' => "Method Not Allowed"
    ];

    header("HTTP/1.0 405 Method Not Allowed");
    echo (json_encode($data));
}
