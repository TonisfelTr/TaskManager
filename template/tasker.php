<?php

if (isset($_GET["type"])){
    if ($_GET["type"] == "tn"){
        include_once "template/files/taskcreator.html";
        $creator = getBrick();
        $main = str_replace_once("{PAGE_NAME}", "Создание новой задачи", $main);
        $main = str_replace_once("{CONTENT_BODY}", $creator, $main);
        $main = str_replace_once("{SUBTITLE}", "Форма для создания новой задачи", $main);
        $main = str_replace_once("{STATUS_ACTIVE_NEW_TASK}", "active", $main);
        include_once "template/files/taskserror.phtml";
        $errors = getBrick();
        $main = str_replace_once("{ERRORS}", $errors, $main);
    }
}

if (empty($_GET) || (isset($_GET["page"]) && count($_GET) == 1)){
    include_once "template/files/tasklist.html";
    $list = getBrick();
    $fi = new FilesystemIterator("tasks/", FilesystemIterator::SKIP_DOTS);
    $count = iterator_count($fi);
    if ($count == 0) {
        $list = str_replace_once("{TASKS_CONTENTS}", "<tr><td colspan='4' style='text-align: center'>Пока не создано ни одной задачи.</td></tr>", $list);
        $list = str_replace_once("{PAGINATION}", "", $list);
    } else {
        $i = ($_GET["page"] == 1 || empty($_GET["page"])) ? 1 : $_GET["page"] * 3 - 2;
        $j = $i +2;
        $tableContent = "";
        for ($i; $i <= $j; $i++){
            if (file_exists("tasks/$i.txt")){
                $rowContent = unserialize(file_get_contents("tasks/$i.txt"));
                $tableContent .= "<tr data-name='" . htmlentities($rowContent[0]) . "' 
                                      data-email='" . htmlentities($rowContent[1]) . "'
                                      data-status='" . (($rowContent[3] == false) ? 0 : 1) . "'>
                                      <td>" . htmlentities($rowContent[0]) . "</td>
                                      <td>" . htmlentities($rowContent[1]) . "</td>
                                      <td>" . htmlentities($rowContent[2]) . "</td>
                                      <td>" . (($rowContent[3] == false) ? "Не выполнено" : "Выполнено") . "</td>
                                  </tr>";
            }
        }
        $list = str_replace_once("{TASKS_CONTENTS}", $tableContent, $list);

        if ($count >= 3){
           $btns = "<div class='btn-group'>";
           for ($i = 1; $i <= ceil($count/3); $i++){
               $btns .= "<a class='btn btn-default' href='?page=$i'>$i</a>";
           }
           $btns .= "</div>";
           $list = str_replace_once("{PAGINATION}", $btns, $list);
        } else {
            $list = str_replace_once("{PAGINATION}", "", $list);
        }
    }
    $list = str_replace_once("{TASKS_CONTENTS}", "", $list);
    $main = str_replace_once("{CONTENT_BODY}", $list, $main);
}