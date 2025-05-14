<?php

    require_once("config.php");
    require_once("signuplogin.php");
    require_once("search.php");
    require_once("signup.php");
    require_once("populateDB");

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($data["type"]) && $data["type"] === "Signup"){

            handleSignup($data);

        } else if (isset($data["type"]) && $data["type"] === "Login"){

            handleLogin($data);

        } else if (isset($data["type"]) && $data["type"] === "Register"){

            handleSearch($data);

        } else if (isset($data["type"]) && data["type"] === "populateDB"){

            populateDB($data);

        }


    } else {
        header("HTTP/1.1 400 Bad Request");
        header("Content-type application/json");
        echo json_encode([
            "status" => "success",
            "timestamp" => $timestamp,
            "data" => $results
        ]);
    }

?>