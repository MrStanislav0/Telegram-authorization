<?php
include_once 'db_connect.php'; // Подключение БД
include_once 'config.php'; //Конфиг

$ssid_uniq = session_id();
$search_ssid = mysql_result(mysql_query("SELECT COUNT(*) FROM `tokens` WHERE `ssid`='".$ssid_uniq."' AND `telegram_id`!='0'"), 0);

if ($search_ssid == 1) // Если это первая авторизация после отправки токена боту
{
    $tokens=mysql_fetch_array(mysql_query("SELECT * FROM `tokens` WHERE `ssid`='".$ssid_uniq."' AND `telegram_id`!='0'")); // Извлекаем токен по ssid
    $user=mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `telegram_id`='".$tokens['telegram_id']."'")); // Получаем данные пользователя
    
    $time = 60*60*24*31; // сколько времени хранить данные в куках 
    setcookie('telegram_id', $user['telegram_id'], time()+$time, '/');
	setcookie('hash', $tokens['hash'], time()+$time, '/');
	session_regenerate_id();
}
else if (!empty($_COOKIE['telegram_id']) AND !empty($_COOKIE['hash'])) // Авторизация по кукам
{
    $telegram_id = SafeString($_COOKIE['telegram_id']);
    $hash = SafeString($_COOKIE['hash']);
    
    $search_user = mysql_result(mysql_query("SELECT COUNT(*) FROM `tokens` WHERE `telegram_id`='".$telegram_id."' AND `hash`='".$hash."'"), 0);
    if ($search_user == 1)
    {
        $user=mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `telegram_id`='".$telegram_id."'"));
    }
}
else
{
  $user = 0;
}

function safeString($string)
{
	if (get_magic_quotes_gpc ())
	{
		$string = stripslashes ($string);
	}	
	return mysql_real_escape_string(htmlspecialchars(trim($string)));
}
?>