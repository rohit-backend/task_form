
<?php
// REST Api to get a specific item using id in URL
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
header("Aceess-Control-Allow-Method: GET");
header("Aceess-Control-Allow-Headers: Content-Type, Aceess-Control-Allow-Headers, Authorisation, X-Request-with");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once("function_api.php");

    if (isset($_GET["id"])) {
        $user_id = $_GET["id"];
        $data = getData($user_id);
        echo $data;
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
