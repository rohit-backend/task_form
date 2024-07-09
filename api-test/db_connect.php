<?php

//for secure connection of with the database with mysqli extension method

function connect_database()
{
    $serverName = "localhost";
    $userName = "root";
    $password = "root";
    $db = "db_innovins";

    $conn = new mysqli($serverName, $userName, $password, $db);

    if ($conn->connect_error) {
        die("Unexpected Error: Fail to Connect with the Database! " . $conn->connect_error);
    }

    return $conn;
}
