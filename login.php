<?php
session_start();

include_once 'db_connect.php'; // Подключение БД
include_once 'config.php'; //Конфиг
include_once 'handler.php'; //Конфиг

if ($user['telegram_id']) //Если пользователь авторизован
{
    header ('Location: page.php');
    exit();
}

$ssid_uniq = session_id();
$search_ssid = mysql_result(mysql_query("SELECT COUNT(*) FROM `tokens` WHERE `ssid`='".$ssid_uniq."'"), 0);
if ($search_ssid == 0)
{
    $hash = md5($ssid_uniq.rand(0, 100000));
    mysql_query("INSERT INTO `tokens` (`ssid`, `hash`) VALUES ('".$ssid_uniq."', '".$hash."')");
}
else
{
    $tokens=mysql_fetch_array(mysql_query("SELECT * FROM `tokens` WHERE `ssid`='".$ssid_uniq."'"));
    $hash = $tokens['hash'];
}
header ('Location: http://telegram.me/'.$name_bot.'?start='.$hash);
?>