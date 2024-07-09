<?php

//Rest api to delete data

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
header("Aceess-Control-Allow-Method: POST");
header("Aceess-Control-Allow-Headers: Content-Type, Aceess-Control-Allow-Headers, Authorisation, X-Request-with");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once("function_api.php");

    if (isset($_POST["id"])) {
        $user_id = $_POST["id"];
        $data = deleteData($id);

        echo ($data);
    } else {
        $data = [
            'status' => 404,
            'message' => "Incorrect URL Method"
        ];

        header("HTTP/1.0 404 Incorrect URL Method");
        echo (json_encode($data));
    }
} else {
    $data = [
        'status' => 405,
        'message' => "Method Not Allowed"
    ];

    header("HTTP/1.0 405 Method Not Allowed");
    echo (json_encode($data));
}
