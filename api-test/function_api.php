<!-- all the API functions are here -->

<?php
require_once("db_connect.php");
$conn = connect_database();
function getData($id)
{
    global $conn;

    $sql = "SELECT * FROM `users` WHERE `id` = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();

        $data = [
            'status' => 200,
            'message' => "Data Fetched Successfully",
            'data' => $row
        ];

        header("HTTP/1.0 200 Data Has been fetched Successfully");
        return (json_encode($data));
    } else {
        $data = [
            'status' => 404,
            'message' => "No Data Found",
        ];

        header("HTTP/1.0 Unable to Fetch Data");
        return (json_encode($data));
    }
}

function getDataAll()
{
    global $conn;

    $sql = "SELECT * FROM `users`";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_all();

        $data = [
            'status' => 200,
            'message' => "Data Fetched Successfully",
            'data' => $row
        ];

        header("HTTP/1.0 200 Data Has been fetched Successfully");
        return (json_encode($data));
    } else {
        $data = [
            'status' => 404,
            'message' => "No Data Found",
        ];

        header("HTTP/1.0 Unable to Fetch Data");
        return (json_encode($data));
    }
}

function addData($email, $name, $password)
{
    global $conn;

    $sql = "INSERT INTO `users` VALUES (NULL, ?, ?, ?, current_timestamp());";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $name, $password);
    $stmt->execute();

    $result = $stmt->get_result();

    $sql_fetch = "SELECT * FROM `users` ORDER BY `id` DESC LIMIT 1;";
    $result_fetch = $conn->query($sql_fetch);
    $row = $result_fetch->fetch_assoc();

    if ($result_fetch) {
        $data = [
            'status' => 200,
            'message' => "Data Added Successfully",
            'data' => $row
        ];
        header("HTTP/1.0 200 Data Has been Added Successfully");
        return (json_encode($data));
    } else {
        $data = [
            'status' => 404,
            'message' => "No Data Found",
        ];

        header("HTTP/1.0 Unable to Fetch Data");
        return (json_encode($data));
    }
}


function updateData($id, $email, $name)
{
    global $conn;

    $sql = "UPDATE `users` SET `email` = ?, `name` = ? WHERE `id` = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $email, $name, $id);
    $stmt->execute();

    $sql_fetch = "SELECT * FROM `users` WHERE id = ?;";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("i", $id);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();

    $row = $result_fetch->fetch_assoc();

    if ($result_fetch) {
        $data = [
            'status' => 200,
            'message' => "Data Updated Successfully",
            'data' => $row
        ];
        header("HTTP/1.0 200 Data Has been Updated Successfully");
        return (json_encode($data));
    } else {
        $data = [
            'status' => 404,
            'message' => "No Data Found",
        ];

        header("HTTP/1.0 Unable to Fetch Data");
        return (json_encode($data));
    }
}

function deleteData($id)
{
    global $conn;

    $sql = "DELETE FROM `users` WHERE `id` = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $data = [
            'status' => 200,
            'message' => "Data Deleted Successfully"
        ];

        header("HTTP/1.0 200 Data Has been Deleted Successfully");
        return (json_encode($data));
    } else {
        $data = [
            'status' => 404,
            'message' => "No Data Found",
        ];

        header("HTTP/1.0 Unable to Fetch Data");
        return (json_encode($data));
    }
}
