<?php

if (isset($_POST["task_create_btn"])){
    if (strlen($_POST["task_author_name"]) < 4 || strlen($_POST["task_author_name"]) > 16){
        header("Location: ../index.php?req=tasker&type=tn&res=nvn");
        exit;
    }

    if (preg_match("/[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+/", $_POST["task_author_email"]) == 0){
        header("Location: ../index.php?req=tasker&type=tn&res=nve");
        exit;
    }

    if (strlen($_POST["task_content"]) < 15){
        header("Location: ../index.php?req=tasker&type=tn&res=nel");
        exit;
    }

    $fi = new FilesystemIterator("../tasks/", FilesystemIterator::SKIP_DOTS);
    $count = iterator_count($fi) + 1;
    file_put_contents("../tasks/" . $count . ".txt", serialize([$_POST["task_author_name"],
        $_POST["task_author_email"],
        $_POST["task_content"],
            false])
        );

    header("Location: ../index.php?req=tasker&type=tn&res=tsc");
    exit;
}

if (isset($_GET["task_complete"])){
    if (!isset($_COOKIE["sid"])){
        header("Location: ../index.php");
        exit;
    }

    $rowContent = unserialize(file_get_contents("../tasks/$_GET[task].txt"));
    if ($rowContent[3] == false){
        $rowContent[3] = true;
    }
    else {
        $rowContent[3] = false;
    }
    file_put_contents("../tasks/$_GET[task].txt", serialize([$rowContent[0],
                                                            $rowContent[1], $rowContent[2], $rowContent[3]]));
    if (empty($_GET["page"]))
        header("Location: ../index.php?req=auth");
    else {
        header("Location: ../index.php?req=auth&page=$_GET[page]");
    }
    exit;
}

header("Location: ../index.php");
exit;



