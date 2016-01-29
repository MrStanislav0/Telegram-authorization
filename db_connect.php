<?php
$hostname = '';;
$database = '';
$username = '';
$password = '';


mysql_connect($hostname, $username, $password) or die('Ошибка соединения с MySQL!');
mysql_select_db($database) or die ('Ошибка соединения с базой данных MySQL!');
mysql_set_charset('utf-8'); // выставляем кодировку базы данных
mb_internal_encoding('utf-8'); // Устанавливаем кодировку строк
setlocale(LC_ALL, 'ru_RU.utf-8'); // Устанавливаем нужную локаль
?>