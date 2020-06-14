<?php

if (!$session) {
    include_once "template/files/auth.html";
    $authForm = getBrick();
    $main = str_replace_once("{PAGE_NAME}", "Авторизация", $main);
    $main = str_replace_once("{CONTENT_BODY}", $authForm, $main);
    $main = str_replace_once("{SUBTITLE}", "Авторизация", $main);
    $main = str_replace_once("{STATUS_ACTIVE_PROFILE}", "active", $main);
    include_once "template/files/errors.phtml";
    $errors = getBrick();
    $main = str_replace_once("{AUTH_ERRORS}", $errors, $main);
} else {
    include_once "template/files/profile.html";
    $profileForm = getBrick();
    $main = str_replace_once("{PAGE_NAME}", "Профиль", $main);
    $main = str_replace_once("{SUBTITLE}", "Профиль Admin", $main);
    $main = str_replace_once("{STATUS_ACTIVE_PROFILE}", "active", $main);
    $fi = new FilesystemIterator("tasks/", FilesystemIterator::SKIP_DOTS);
    $count = iterator_count($fi);
    if ($count == 0) {
        $profileForm = str_replace_once("{TASKS_CONTENTS}", "<tr><td colspan='4' style='text-align: center'>Пока не создано ни одной задачи.</td></tr>", $profileForm);
        $profileForm = str_replace_once("{PAGINATION}", "", $profileForm);
    } else {
        $i = ($_GET["page"] == 1 || empty($_GET["page"])) ? 1 : $_GET["page"] * 3 - 2;
        $j = $i +2;
        $tableContent = "";
        for ($i; $i <= $j; $i++){
            if (file_exists("tasks/$i.txt")){
                $rowContent = unserialize(file_get_contents("tasks/$i.txt"));
                $tableContent .= "<tr>
                                      <td>" . htmlentities($rowContent[0]) . "</td>
                                      <td>" . htmlentities($rowContent[1]) . "</td>
                                      <td>" . htmlentities($rowContent[2]) . "</td>
                                      <td><button class='btn btn-default' type='submit' formaction='scripts/tasker.php?task_complete&page=$_GET[page]&task=$i'>" . (($rowContent[3] == false) ? "Не выполнено" : "Выполнено") . "</button></td>
                                  </tr>";
            }
        }
        $profileForm = str_replace_once("{TASKS_CONTENTS}", $tableContent, $profileForm);

        if ($count >= 3){
            $btns = "<div class='btn-group'>";
            for ($i = 1; $i <= ceil($count/3); $i++){
                $btns .= "<a class='btn btn-default' href='?page=$i&req=auth'>$i</a>";
            }
            $btns .= "</div>";
            $profileForm = str_replace_once("{PAGINATION}", $btns, $profileForm);
        } else {
            $profileForm = str_replace_once("{PAGINATION}", "", $profileForm);
        }
    }
    $profileForm = str_replace_once("{TASKS_CONTENTS}", "", $profileForm);
    $main = str_replace_once("{CONTENT_BODY}", $profileForm, $main);
}