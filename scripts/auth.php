<?php

if (isset($_POST["auth_nickname"])){
    if ($_POST["auth_nickname"] == "") {
        header("Location: ../?req=auth&res=iu");
        exit;
    }
    if (strtolower($_POST["auth_nickname"]) != "admin"){
        header("Location: ../?req=auth&res=une");
        exit;
    }
}

if (isset($_POST["auth_password"])){
    if ($_POST["auth_password"] == "") {
        header("Location: ../?req=auth&res=ip");
        exit;
    }

    if ($_POST["auth_password"] != "123") {
        header("Location: ../?req=auth&res=pii");
        exit;
    }
}
if (isset($_POST["auth_nickname"]) && isset($_POST["auth_password"])) {
    if (strtolower($_POST["auth_nickname"]) == "admin" && $_POST["auth_password"] == "123") {
        session_start();
        setcookie("sid", session_id(), time() + 3600, "/");
        header("Location: ../?req=auth");
        exit;
    }
}

if (isset($_POST["profile_exit"])){
    setcookie("sid", session_id(), 1, "/");
    session_destroy();
    header("Location: ../index.php");
    exit;
}