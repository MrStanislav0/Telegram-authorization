<?php
session_start();

include_once 'db_connect.php'; // Подключение БД
include_once 'config.php'; //Конфиг
include_once 'handler.php'; //Конфиг


// проверяем авторизацию пользователя 
if ($user)
{
    $ssid_uniq = session_id();
    mysql_query("DELETE FROM `tokens` WHERE `ssid`='".$ssid_uniq."'");
    setcookie('telegram_id', '', time()-1, '/'); 
    setcookie('hash', '', time()-1, '/');
    header ('Location: index.php');
} 
else 
{
    header ('Location: index.php');
}
session_destroy(); 
?>