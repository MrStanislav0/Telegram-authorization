<?php
include_once 'db_connect.php'; // Подключение БД
include_once 'config.php'; //Конфиг

$response = json_decode(file_get_contents('php://input'), TRUE); // Массив с данными

// Проверяем, правильный ли запрос получен

if (!$response)
{
  exit();
}

// Проверяем, сообщение из личного чата или нет

$type = $response['message']['chat']['type'];
if ($type != 'private')
{
  exit();
}

$text = $response['message']['text']; // Текст сообщения

$command = mb_substr($text, 0, 7);
if ($command == '/start ') // Проверяем хеш для авторизации пользователя
{
  $hash = safeString(substr($text, 7));
  $search_hash = mysql_result(mysql_query("SELECT COUNT(*) FROM `tokens` WHERE `hash`='".$hash."'"), 0);
  if ($search_hash != 1)
  {
    exit();
  }
    
  // Записываем информацию о пользователе в БД
  
  $chat_id = $response['message']['chat']['id']; // Telegram ID пользователя
  $first_name = safeString($response['message']['chat']['first_name']); // Имя пользователя
  $last_name = safeString($response['message']['chat']['last_name']); // Фамилия пользователя
  $username = $response['message']['chat']['username']; // @username пользователя
  
  getPhoto($chat_id); // получаем аватарку пользователя
  
  $search_user = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `telegram_id`='".$chat_id."'"), 0);
  mysql_query("UPDATE `tokens` SET `telegram_id`='".$chat_id."' WHERE `hash`='".$hash."'");
  if ($search_user == 0)
  {
    mysql_query("INSERT INTO `users` (`telegram_id`, `first_name`, `last_name`, `username`) VALUES ('".$chat_id."', '".$first_name."', '".$last_name."', '".$username."')");
  }
  else
  {
    mysql_query("UPDATE `users` SET `first_name`='".$first_name."', `last_name`='".$last_name."', `username`='".$username."' WHERE `telegram_id`='".$chat_id."'");
  }
  
  $message = 'Вы успешно авторизовались с помощью Telegram на сайте '.$_SERVER['HTTP_HOST'].'!
Можете вернуться в браузер и обновить страницу.';
  sendMessage($chat_id, $message);
}
else if ($text == '/info') // Отправляем информацию о полученных данных, если пользователь ввел /info
{
  $chat_id = $response['message']['chat']['id']; // Telegram ID пользователя
  $message = 'Авторизовавшись на сайте '.$_SERVER['HTTP_HOST'].', вы передали следующие данные:
Telegram ID, Имя, Фамилию, @username и аватар';
  sendMessage($chat_id, $message);
}
else if ($text == '/exit') // Выход со всех устройств, если пользователь ввел /exit
{
  $chat_id = $response['message']['chat']['id']; // Telegram ID пользователя
  mysql_query("DELETE FROM `tokens` WHERE `telegram_id`='".$chat_id."'");
  
  $message = 'Вы успешно вышли со всех устройств!';
  sendMessage($chat_id, $message);
}
else
{
  exit();
}

// Функция отправки сообщений

function sendMessage($chat_id, $message)
{
  $message=urlencode($message);
  file_get_contents($GLOBALS['api'].'/sendMessage?chat_id='.$chat_id.'&text='.$message);
}

// Получение аватарки пользователя

function getPhoto($chat_id)
{
  $out = json_decode(file_get_contents($GLOBALS['api'].'/getUserProfilePhotos?user_id='.$chat_id), TRUE);
  
  if (!$out['result']['photos']['0']['0']['file_id']) // Проверяем, есть ли аватарка у пользователя
  {
    mysql_query("UPDATE `users` SET `avatarka`='no.jpg' WHERE `telegram_id`='".$chat_id."'");
    return;
  }
  
  $path = getFilePath($out['result']['photos']['0']['0']['file_id']);
  $file = 'https://api.telegram.org/file/bot'.$GLOBALS['access_token'].'/'.$path;
  $newfile = $chat_id.'.jpg';
  mysql_query("UPDATE `users` SET `avatarka`='".$newfile."' WHERE `telegram_id`='".$chat_id."'");
  copy($file, $newfile);
}

// Функция получения пути для скачивания файла (аватарки)

function getFilePath($file_id)
{
  $out = json_decode(file_get_contents($GLOBALS['api'].'/getFile?file_id='.$file_id), TRUE);
  $path = stripslashes($out['result']['file_path']);
  return $path;
}

// Функция экранирует специальные символы, преобразует специальные символы в HTML сущности

function safeString($string)
{
	if (get_magic_quotes_gpc ())
	{
		$string = stripslashes ($string);
	}	
	return mysql_real_escape_string(htmlspecialchars(trim($string)));
}
?>