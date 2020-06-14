<?php
function getBrick(){
    $e = ob_get_contents();
    ob_clean();
    return $e;
}

function str_replace_once($search, $replace, $text){
    $pos = strpos($text, $search);
    return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
}
$session = false;
if (isset($_COOKIE["sid"])){
    session_start($_COOKIE["sid"]);
    $session = true;
}
ob_start();
include_once "./template/files/main.html";
$main = getBrick();
if (isset($_GET["task"])) {
    $main = str_replace_once("{PAGE_NAME}", "Просмотр задания", $main);
    $main = str_replace_once("{SUBTITLE}", "Просмотр задания", $main);
    $main = str_replace_once("{STATUS_ACTIVE_HOME}", "", $main);
    $main = str_replace_once("{STATUS_ACTIVE_PROFILE}", "", $main);
    if ($_GET["task"] <= 0)
        $main = str_replace_once("{CONTENT_BODY}", "Запрашиваемой задачи не существует.", $main);
}
elseif (isset($_GET["req"])) {
   if (file_exists("template/" . $_GET["req"] . ".php")) {
       include_once "template/" . $_GET["req"] . ".php";
   } else {
       $main = str_replace_once("{PAGE_NAME}", "404 Страница не найдена", $main);
       $main = str_replace_once("{CONTENT_BODY}", "Данной страницы не существует.", $main);
       $main = str_replace_once("{SUBTITLE}", "Страница не найдена", $main);
       $main = str_replace_once("{STATUS_ACTIVE_PROFILE}", "", $main);
       $main = str_replace_once("{STATUS_ACTIVE_HOME}", "", $main);
   }
}
elseif (empty($_GET) || (count($_GET) == 1 && isset($_GET["page"]))) {
    $main = str_replace_once("{PAGE_NAME}", "Главная", $main);
    $main = str_replace_once("{SUBTITLE}", "Список задач", $main);
    include_once "template/tasker.php";
    $table = getBrick();
    $main = str_replace_once("{CONTENT_BODY}", $table, $main);
}
$main = str_replace_once("{PROFILE}", "Профиль", $main);
ob_end_clean();
echo $main;